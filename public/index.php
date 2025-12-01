<?php
// public/index.php

require_once '../config/config.php';
require_once '../config/db.php';
require_once '../includes/functions.php';
require_once '../includes/Auth.php';
require_once '../includes/Tenant.php';

// Initialize Database
$database = new Database();
$db = $database->getConnection();

// Initialize Auth & Tenant
$auth = new Auth($db);
$tenantObj = new Tenant($db);

// Determine page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Logic handling variables
$error_message = '';
$success_message = '';
// Check for passed error via GET
if (isset($_GET['error']) && $_GET['error'] === 'LimitReached') {
    $error_message = 'Subscription Limit Reached for this action.';
}

$view_to_include = 'home'; // Default view

// Data containers
$incidents = [];
$problems = [];
$changes = [];
$requests = [];
$cis = [];
$users_list = [];
$ci_types = [];
$change_types = [];
$services = [];
$incident = null;
$comments = [];
$tech_users = [];
$unread_notifications_count = 0;
$notifications = [];

// --- GLOBAL RBAC CHECK ---
// Protected pages require login
$public_pages = ['home', 'login', 'register_tenant'];
if (!in_array($page, $public_pages)) {
    $auth->requireLogin();

    // Feature-specific permission check
    // Map pages to 'modules' defined in Auth::hasPermission
    $module_map = [
        'dashboard' => 'dashboard',
        'create_incident' => 'create_incident',
        'view_incident' => 'view_incident',
        'edit_incident' => 'view_incident', // technicians only essentially

        'problem_list' => 'problems',
        'create_problem' => 'problems',

        'change_list' => 'changes',
        'create_change' => 'changes',

        'cmdb_list' => 'cmdb',
        'create_ci' => 'cmdb',

        'request_list' => 'request_list',
        'create_request' => 'create_request',

        'user_list' => 'users',
        'create_user' => 'users',
        'settings' => 'dashboard', // Assuming settings accessible to all, or refine
        'reports' => 'dashboard', // Assuming reports accessible, or refine to 'reports' module

        'logout' => 'dashboard' // accessible by all logged in
    ];

    $module = $module_map[$page] ?? 'dashboard';

    if (!$auth->hasPermission($module)) {
        // Fallback or deny access
        $error_message = "Access Denied: You do not have permission to view this page.";
        // Force them to dashboard if they try to access something unauthorized
        $page = 'dashboard';
        $view_to_include = 'dashboard';
    }
}

$user = $auth->getCurrentUser();

// --- LOGIC BLOCK START ---

// Fetch Notifications if Logged In
if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    $notif_sql = "SELECT * FROM notifications WHERE user_id = :user_id AND tenant_id = :tenant_id AND is_read = 0 ORDER BY sent_at DESC LIMIT 5";
    $stmt_notif = $db->prepare($notif_sql);
    $stmt_notif->bindParam(':user_id', $user['user_id']);
    $stmt_notif->bindParam(':tenant_id', $user['tenant_id']);
    $stmt_notif->execute();
    $notifications = $stmt_notif->fetchAll(PDO::FETCH_ASSOC);

    // Count unread
    $count_sql = "SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND tenant_id = :tenant_id AND is_read = 0";
    $stmt_count = $db->prepare($count_sql);
    $stmt_count->bindParam(':user_id', $user['user_id']);
    $stmt_count->bindParam(':tenant_id', $user['tenant_id']);
    $stmt_count->execute();
    $unread_notifications_count = $stmt_count->fetchColumn();
}

switch ($page) {
    case 'login':
        if ($auth->isLoggedIn()) {
             redirect('index.php?page=dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenant_code = $_POST['tenant_code'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $tenant = $tenantObj->getTenantByCode($tenant_code);

            if ($tenant) {
                if ($auth->login($email, $password, $tenant['tenant_id'])) {
                    redirect('index.php?page=dashboard');
                } else {
                    $error_message = 'Invalid credentials.';
                }
            } else {
                 $error_message = 'Invalid Tenant Code.';
            }
        }
        $view_to_include = 'login';
        break;

    case 'logout':
        $auth->logout();
        redirect('index.php?page=login');
        break;

    case 'register_tenant':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Basic validation
            $data = [
                'company_name' => sanitize_input($_POST['company_name']),
                'tenant_code' => sanitize_input($_POST['tenant_code']),
                'subdomain' => sanitize_input($_POST['subdomain']),
                'contact_email' => sanitize_input($_POST['contact_email']),
                'subscription_plan' => sanitize_input($_POST['subscription_plan']),
            ];

            // Check if tenant code exists
            if ($tenantObj->getTenantByCode($data['tenant_code'])) {
                 $error_message = 'Tenant Code already exists.';
            } else {
                $tenant_id = $tenantObj->createTenant($data);
                if ($tenant_id) {
                    // Create Admin User
                    $admin_username = sanitize_input($_POST['admin_username']);
                    $admin_password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);

                    $query = "INSERT INTO users (tenant_id, username, email, password_hash, first_name, last_name, role)
                              VALUES (:tenant_id, :username, :email, :password_hash, 'Admin', 'User', 'tenant_admin')";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':tenant_id', $tenant_id);
                    $stmt->bindParam(':username', $admin_username);
                    $stmt->bindParam(':email', $data['contact_email']); // Using contact email for admin
                    $stmt->bindParam(':password_hash', $admin_password);

                    if ($stmt->execute()) {
                         $success_message = 'Organization registered successfully! Please login.';
                    } else {
                         $error_message = 'Organization created but failed to create admin user.';
                    }
                } else {
                    $error_message = 'Failed to create organization.';
                }
            }
        }
        $view_to_include = 'register_tenant';
        break;

    case 'dashboard':
        // Dashboard uses same data as incidents but limited, plus maybe KPIs (mocked in view for now)
        // Re-using query logic but limiting to 5 for "Recent"

        $sql = "SELECT i.*, p.priority_name, u.first_name as assigned_name
                FROM incidents i
                LEFT JOIN priority_matrix p ON i.priority_id = p.priority_id
                LEFT JOIN users u ON i.assigned_to = u.user_id
                WHERE i.tenant_id = :tenant_id ";

        if ($user['role'] === 'end_user') {
            $sql .= " AND i.reported_by = :user_id ";
        }

        $sql .= " ORDER BY i.created_at DESC LIMIT 5";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        if ($user['role'] === 'end_user') {
            $stmt->bindParam(':user_id', $user['user_id']);
        }
        $stmt->execute();
        $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view_to_include = 'dashboard';
        break;

    case 'incidents':
        // Full list logic
        $sql = "SELECT i.*, p.priority_name, u.first_name as assigned_name
                FROM incidents i
                LEFT JOIN priority_matrix p ON i.priority_id = p.priority_id
                LEFT JOIN users u ON i.assigned_to = u.user_id
                WHERE i.tenant_id = :tenant_id ";

        if ($user['role'] === 'end_user') {
            $sql .= " AND i.reported_by = :user_id ";
        }

        $sql .= " ORDER BY i.created_at DESC LIMIT 50";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        if ($user['role'] === 'end_user') {
            $stmt->bindParam(':user_id', $user['user_id']);
        }
        $stmt->execute();
        $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view_to_include = 'incident_list';
        break;

    case 'create_incident':
        // CHECK SUBSCRIPTION LIMIT
        if (!$tenantObj->checkUsageLimit($user['tenant_id'], 'incidents')) {
             redirect('index.php?page=dashboard&error=LimitReached');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $description = sanitize_input($_POST['description']);
            $urgency = sanitize_input($_POST['urgency']);
            $impact = sanitize_input($_POST['impact']);

            $incident_number = 'INC-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // Find Priority
            $query_prio = "SELECT priority_id FROM priority_matrix WHERE impact = :impact AND urgency = :urgency AND (tenant_id = :tenant_id OR tenant_id IS NULL) ORDER BY tenant_id DESC LIMIT 1";
            $stmt_prio = $db->prepare($query_prio);
            $stmt_prio->bindParam(':impact', $impact);
            $stmt_prio->bindParam(':urgency', $urgency);
            $stmt_prio->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_prio->execute();
            $prio = $stmt_prio->fetch(PDO::FETCH_ASSOC);
            $priority_id = $prio ? $prio['priority_id'] : null;

            $query = "INSERT INTO incidents (tenant_id, incident_number, title, description, reported_by, impact, urgency, priority_id)
                      VALUES (:tenant_id, :incident_number, :title, :description, :reported_by, :impact, :urgency, :priority_id)";

            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':incident_number', $incident_number);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':reported_by', $user['user_id']);
            $stmt->bindParam(':impact', $impact);
            $stmt->bindParam(':urgency', $urgency);
            $stmt->bindParam(':priority_id', $priority_id);

            if ($stmt->execute()) {
                $new_incident_id = $db->lastInsertId();
                // Notify Reporter
                create_notification($db, $user['tenant_id'], $user['user_id'], 'incident', $new_incident_id, 'update', 'Incident Created', "Your incident #$incident_number has been created.");

                redirect('index.php?page=dashboard');
            } else {
                 $error_message = 'Failed to create incident.';
            }
        }
        $view_to_include = 'create_incident';
        break;

    case 'view_incident':
        $incident_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Fetch Incident Details
        $query = "SELECT i.*, p.priority_name, u_rep.first_name as reporter_name, u_assign.first_name as assigned_name
                  FROM incidents i
                  LEFT JOIN priority_matrix p ON i.priority_id = p.priority_id
                  LEFT JOIN users u_rep ON i.reported_by = u_rep.user_id
                  LEFT JOIN users u_assign ON i.assigned_to = u_assign.user_id
                  WHERE i.incident_id = :id AND i.tenant_id = :tenant_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $incident_id);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $incident = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$incident) {
            $error_message = "Incident not found.";
            $view_to_include = 'dashboard';
            break;
        }

        // Security Check: End Users can only view their own
        if ($user['role'] === 'end_user' && $incident['reported_by'] != $user['user_id']) {
            $error_message = "Access Denied.";
            $view_to_include = 'dashboard';
            break;
        }

        // Handle Comment Submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment_text = sanitize_input($_POST['comment_text']);
            $is_internal = isset($_POST['is_internal']) ? 1 : 0;

            // Force internal to false if end_user (shouldn't happen due to form, but good for security)
            if ($user['role'] === 'end_user') $is_internal = 0;

            $query = "INSERT INTO comments (tenant_id, ticket_type, ticket_id, comment_text, is_internal, created_by)
                      VALUES (:tenant_id, 'incident', :ticket_id, :comment_text, :is_internal, :created_by)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':ticket_id', $incident_id);
            $stmt->bindParam(':comment_text', $comment_text);
            $stmt->bindParam(':is_internal', $is_internal);
            $stmt->bindParam(':created_by', $user['user_id']);

            if ($stmt->execute()) {
                $success_message = "Comment added.";
            }
        }

        // Fetch Comments
        $query = "SELECT c.*, u.first_name as commenter_name
                  FROM comments c
                  JOIN users u ON c.created_by = u.user_id
                  WHERE c.ticket_type = 'incident' AND c.ticket_id = :id AND c.tenant_id = :tenant_id
                  ORDER BY c.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $incident_id);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view_to_include = 'view_incident';
        break;

    case 'edit_incident':
        // Only allow technicians/admins
        if ($user['role'] === 'end_user') {
            redirect('index.php?page=dashboard&error=AccessDenied');
        }

        $incident_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Fetch Incident Details to Populate Form
        $query = "SELECT * FROM incidents WHERE incident_id = :id AND tenant_id = :tenant_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $incident_id);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $incident = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$incident) {
            redirect('index.php?page=dashboard&error=NotFound');
        }

        // Fetch Technicians for assignment dropdown
        $query_techs = "SELECT user_id, first_name, last_name FROM users
                        WHERE tenant_id = :tenant_id AND role IN ('technician', 'admin', 'manager', 'tenant_admin')";
        $stmt_techs = $db->prepare($query_techs);
        $stmt_techs->bindParam(':tenant_id', $user['tenant_id']);
        $stmt_techs->execute();
        $tech_users = $stmt_techs->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $status = sanitize_input($_POST['status']);
            $assigned_to = !empty($_POST['assigned_to']) ? sanitize_input($_POST['assigned_to']) : null;
            $impact = sanitize_input($_POST['impact']);
            $urgency = sanitize_input($_POST['urgency']);

            // Recalculate priority
            $query_prio = "SELECT priority_id FROM priority_matrix WHERE impact = :impact AND urgency = :urgency AND (tenant_id = :tenant_id OR tenant_id IS NULL) ORDER BY tenant_id DESC LIMIT 1";
            $stmt_prio = $db->prepare($query_prio);
            $stmt_prio->bindParam(':impact', $impact);
            $stmt_prio->bindParam(':urgency', $urgency);
            $stmt_prio->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_prio->execute();
            $prio = $stmt_prio->fetch(PDO::FETCH_ASSOC);
            $priority_id = $prio ? $prio['priority_id'] : $incident['priority_id'];

            $update_sql = "UPDATE incidents SET
                           title = :title,
                           status = :status,
                           assigned_to = :assigned_to,
                           impact = :impact,
                           urgency = :urgency,
                           priority_id = :priority_id,
                           updated_at = NOW()
                           WHERE incident_id = :id AND tenant_id = :tenant_id";

            $stmt_upd = $db->prepare($update_sql);
            $stmt_upd->bindParam(':title', $title);
            $stmt_upd->bindParam(':status', $status);
            $stmt_upd->bindParam(':assigned_to', $assigned_to);
            $stmt_upd->bindParam(':impact', $impact);
            $stmt_upd->bindParam(':urgency', $urgency);
            $stmt_upd->bindParam(':priority_id', $priority_id);
            $stmt_upd->bindParam(':id', $incident_id);
            $stmt_upd->bindParam(':tenant_id', $user['tenant_id']);

            if ($stmt_upd->execute()) {
                 // Notify Assignee if changed
                 if ($assigned_to && $assigned_to != $incident['assigned_to']) {
                     create_notification($db, $user['tenant_id'], $assigned_to, 'incident', $incident_id, 'assignment', 'Incident Assigned', "You have been assigned incident " . $incident['incident_number']);
                 }
                 // Notify Reporter on status change or resolution
                 if ($status != $incident['status']) {
                     create_notification($db, $user['tenant_id'], $incident['reported_by'], 'incident', $incident_id, 'update', 'Incident Updated', "Incident " . $incident['incident_number'] . " status changed to " . ucfirst($status));
                 }

                 redirect('index.php?page=view_incident&id='.$incident_id);
            } else {
                 $error_message = "Failed to update incident.";
            }
        }

        $view_to_include = 'edit_incident';
        break;

    // --- CMDB ---
    case 'cmdb_list':
        $query = "SELECT ci.*, t.type_name FROM configuration_items ci
                  LEFT JOIN ci_types t ON ci.ci_type_id = t.ci_type_id
                  WHERE ci.tenant_id = :tenant_id ORDER BY ci.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $cis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'cmdb_list';
        break;

    case 'create_ci':
        // Load CI Types
        $query_types = "SELECT * FROM ci_types WHERE tenant_id = :tenant_id OR tenant_id IS NULL";
        $stmt_types = $db->prepare($query_types);
        $stmt_types->bindParam(':tenant_id', $user['tenant_id']);
        $stmt_types->execute();
        $ci_types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ci_name = sanitize_input($_POST['ci_name']);
            $ci_type_id = sanitize_input($_POST['ci_type_id']);
            $description = sanitize_input($_POST['description']);
            $status = sanitize_input($_POST['status']);
            $criticality = sanitize_input($_POST['criticality']);
            $ci_number = 'CI-' . strtoupper(substr(md5(uniqid()), 0, 8));

            $query = "INSERT INTO configuration_items (tenant_id, ci_number, ci_name, ci_type_id, description, status, criticality, owner_id)
                      VALUES (:tenant_id, :ci_number, :ci_name, :ci_type_id, :description, :status, :criticality, :owner_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':ci_number', $ci_number);
            $stmt->bindParam(':ci_name', $ci_name);
            $stmt->bindParam(':ci_type_id', $ci_type_id);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':criticality', $criticality);
            $stmt->bindParam(':owner_id', $user['user_id']);

            if ($stmt->execute()) {
                redirect('index.php?page=cmdb_list');
            } else {
                $error_message = 'Failed to create CI.';
            }
        }
        $view_to_include = 'create_ci';
        break;

    // --- Problem Management ---
    case 'problem_list':
        $query = "SELECT * FROM problems WHERE tenant_id = :tenant_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'problem_list';
        break;

    case 'create_problem':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $description = sanitize_input($_POST['description']);
            $impact = sanitize_input($_POST['impact']);
            $urgency = sanitize_input($_POST['urgency']);
            $root_cause = sanitize_input($_POST['root_cause']);
            $problem_number = 'PRB-' . strtoupper(substr(md5(uniqid()), 0, 8));

             // Find Priority
            $query_prio = "SELECT priority_id FROM priority_matrix WHERE impact = :impact AND urgency = :urgency AND (tenant_id = :tenant_id OR tenant_id IS NULL) ORDER BY tenant_id DESC LIMIT 1";
            $stmt_prio = $db->prepare($query_prio);
            $stmt_prio->bindParam(':impact', $impact);
            $stmt_prio->bindParam(':urgency', $urgency);
            $stmt_prio->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_prio->execute();
            $prio = $stmt_prio->fetch(PDO::FETCH_ASSOC);
            $priority_id = $prio ? $prio['priority_id'] : null;

            $query = "INSERT INTO problems (tenant_id, problem_number, title, description, identified_by, impact, urgency, priority_id, root_cause)
                      VALUES (:tenant_id, :problem_number, :title, :description, :identified_by, :impact, :urgency, :priority_id, :root_cause)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':problem_number', $problem_number);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':identified_by', $user['user_id']);
            $stmt->bindParam(':impact', $impact);
            $stmt->bindParam(':urgency', $urgency);
            $stmt->bindParam(':priority_id', $priority_id);
            $stmt->bindParam(':root_cause', $root_cause);

            if ($stmt->execute()) {
                redirect('index.php?page=problem_list');
            } else {
                $error_message = 'Failed to create Problem.';
            }
        }
        $view_to_include = 'create_problem';
        break;

    // --- Change Management ---
    case 'change_list':
        $query = "SELECT c.*, t.type_name FROM changes c
                  LEFT JOIN change_types t ON c.change_type_id = t.change_type_id
                  WHERE c.tenant_id = :tenant_id ORDER BY c.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $changes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'change_list';
        break;

    case 'create_change':
        // Load Change Types
        $query_types = "SELECT * FROM change_types WHERE tenant_id = :tenant_id OR tenant_id IS NULL";
        $stmt_types = $db->prepare($query_types);
        $stmt_types->bindParam(':tenant_id', $user['tenant_id']);
        $stmt_types->execute();
        $change_types = $stmt_types->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $description = sanitize_input($_POST['description']);
            $change_type_id = sanitize_input($_POST['change_type_id']);
            $risk_level = sanitize_input($_POST['risk_level']);
            $reason = sanitize_input($_POST['reason_for_change']);
            $backout = sanitize_input($_POST['backout_plan']);
            $impact = sanitize_input($_POST['impact']);
            $urgency = sanitize_input($_POST['urgency']);
            $change_number = 'CHG-' . strtoupper(substr(md5(uniqid()), 0, 8));

             // Find Priority
            $query_prio = "SELECT priority_id FROM priority_matrix WHERE impact = :impact AND urgency = :urgency AND (tenant_id = :tenant_id OR tenant_id IS NULL) ORDER BY tenant_id DESC LIMIT 1";
            $stmt_prio = $db->prepare($query_prio);
            $stmt_prio->bindParam(':impact', $impact);
            $stmt_prio->bindParam(':urgency', $urgency);
            $stmt_prio->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_prio->execute();
            $prio = $stmt_prio->fetch(PDO::FETCH_ASSOC);
            $priority_id = $prio ? $prio['priority_id'] : null;


            $query = "INSERT INTO changes (tenant_id, change_number, title, description, change_type_id, risk_level, reason_for_change, backout_plan, requested_by, impact, urgency, priority_id)
                      VALUES (:tenant_id, :change_number, :title, :description, :change_type_id, :risk_level, :reason, :backout, :requested_by, :impact, :urgency, :priority_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':change_number', $change_number);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':change_type_id', $change_type_id);
            $stmt->bindParam(':risk_level', $risk_level);
            $stmt->bindParam(':reason', $reason);
            $stmt->bindParam(':backout', $backout);
            $stmt->bindParam(':requested_by', $user['user_id']);
            $stmt->bindParam(':impact', $impact);
            $stmt->bindParam(':urgency', $urgency);
            $stmt->bindParam(':priority_id', $priority_id);


            if ($stmt->execute()) {
                redirect('index.php?page=change_list');
            } else {
                $error_message = 'Failed to create Change request: ' . implode(" ", $stmt->errorInfo());
            }
        }
        $view_to_include = 'create_change';
        break;

    // --- Service Requests ---
    case 'request_list':
        $query = "SELECT r.*, s.service_name FROM service_requests r
                  LEFT JOIN service_catalog s ON r.service_id = s.service_id
                  WHERE r.tenant_id = :tenant_id ";

         if ($user['role'] === 'end_user') {
            $query .= " AND r.requested_by = :user_id ";
        }
        $query .= " ORDER BY r.requested_date DESC";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        if ($user['role'] === 'end_user') {
            $stmt->bindParam(':user_id', $user['user_id']);
        }
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'request_list';
        break;

    case 'create_request':
        // Load Services
        $query_services = "SELECT * FROM service_catalog WHERE tenant_id = :tenant_id AND is_active = 1";
        $stmt_services = $db->prepare($query_services);
        $stmt_services->bindParam(':tenant_id', $user['tenant_id']);
        $stmt_services->execute();
        $services = $stmt_services->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $description = sanitize_input($_POST['description']);
            $service_id = !empty($_POST['service_id']) ? sanitize_input($_POST['service_id']) : null;
            $impact = sanitize_input($_POST['impact']);
            $urgency = sanitize_input($_POST['urgency']);
            $request_number = 'REQ-' . strtoupper(substr(md5(uniqid()), 0, 8));

             // Find Priority
            $query_prio = "SELECT priority_id FROM priority_matrix WHERE impact = :impact AND urgency = :urgency AND (tenant_id = :tenant_id OR tenant_id IS NULL) ORDER BY tenant_id DESC LIMIT 1";
            $stmt_prio = $db->prepare($query_prio);
            $stmt_prio->bindParam(':impact', $impact);
            $stmt_prio->bindParam(':urgency', $urgency);
            $stmt_prio->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_prio->execute();
            $prio = $stmt_prio->fetch(PDO::FETCH_ASSOC);
            $priority_id = $prio ? $prio['priority_id'] : null;

            $query = "INSERT INTO service_requests (tenant_id, request_number, title, description, service_id, requested_by, impact, urgency, priority_id)
                      VALUES (:tenant_id, :request_number, :title, :description, :service_id, :requested_by, :impact, :urgency, :priority_id)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':tenant_id', $user['tenant_id']);
            $stmt->bindParam(':request_number', $request_number);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':service_id', $service_id);
            $stmt->bindParam(':requested_by', $user['user_id']);
             $stmt->bindParam(':impact', $impact);
            $stmt->bindParam(':urgency', $urgency);
            $stmt->bindParam(':priority_id', $priority_id);


            if ($stmt->execute()) {
                redirect('index.php?page=request_list');
            } else {
                $error_message = 'Failed to create Service Request.';
            }
        }
        $view_to_include = 'create_request';
        break;

    // --- User Management ---
    case 'user_list':
        $query = "SELECT * FROM users WHERE tenant_id = :tenant_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'user_list';
        break;

    case 'create_user':
        // CHECK SUBSCRIPTION LIMIT
        if (!$tenantObj->checkUsageLimit($user['tenant_id'], 'users')) {
             redirect('index.php?page=user_list&error=LimitReached');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = sanitize_input($_POST['first_name']);
            $last_name = sanitize_input($_POST['last_name']);
            $username = sanitize_input($_POST['username']);
            $email = sanitize_input($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = sanitize_input($_POST['role']);

            // Check duplicate
            $check = "SELECT user_id FROM users WHERE tenant_id = :tenant_id AND (username = :username OR email = :email)";
            $stmt_check = $db->prepare($check);
            $stmt_check->bindParam(':tenant_id', $user['tenant_id']);
            $stmt_check->bindParam(':username', $username);
            $stmt_check->bindParam(':email', $email);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $error_message = 'Username or Email already exists.';
            } else {
                $query = "INSERT INTO users (tenant_id, first_name, last_name, username, email, password_hash, role)
                          VALUES (:tenant_id, :first_name, :last_name, :username, :email, :password_hash, :role)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':tenant_id', $user['tenant_id']);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password_hash', $password);
                $stmt->bindParam(':role', $role);

                if ($stmt->execute()) {
                    redirect('index.php?page=user_list');
                } else {
                    $error_message = 'Failed to create User.';
                }
            }
        }
        $view_to_include = 'create_user';
        break;

    case 'settings':
        $view_to_include = 'settings';
        break;

    case 'reports':
        $view_to_include = 'reports';
        break;

    case 'home':
    default:
        // Use default view
        break;
}

// --- OUTPUT BLOCK START ---

include '../templates/header.php';

// Display messages
if (!empty($error_message)) {
    echo '<div class="alert alert-danger">' . $error_message . '</div>';
}
if (!empty($success_message)) {
    echo '<div class="alert alert-success">' . $success_message . '</div>';
}

// Include the determined view
if ($view_to_include === 'home') {
    echo '<div class="card">
            <h2>Welcome to ITIL Service Desk</h2>
            <p>Please <a href="index.php?page=login">Login</a> or <a href="index.php?page=register_tenant">Register your Organization</a>.</p>
          </div>';
} else {
    // Check if file exists to be safe
    $view_path = '../templates/' . $view_to_include . '.php';
    if (file_exists($view_path)) {
        include $view_path;
    } else {
        echo '<div class="alert alert-danger">Page not found.</div>';
    }
}

include '../templates/footer.php';
