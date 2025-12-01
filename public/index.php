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

// Initialize Auth
$auth = new Auth($db);

// Determine page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Logic handling variables
$error_message = '';
$success_message = '';
$view_to_include = 'home'; // Default view
$incidents = [];
$problems = [];
$changes = [];
$requests = [];
$cis = [];
$users_list = [];
$ci_types = [];
$change_types = [];
$services = [];

// --- LOGIC BLOCK START ---

switch ($page) {
    case 'login':
        if ($auth->isLoggedIn()) {
             redirect('index.php?page=dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenant_code = $_POST['tenant_code'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $tenantObj = new Tenant($db);
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
            $tenantObj = new Tenant($db);
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
                         // Optionally redirect to login, but showing success here is fine for now
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

        $query = "SELECT i.*, p.priority_name
                  FROM incidents i
                  LEFT JOIN priority_matrix p ON i.priority_id = p.priority_id
                  WHERE i.tenant_id = :tenant_id
                  ORDER BY i.created_at DESC LIMIT 20";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view_to_include = 'dashboard';
        break;

    case 'create_incident':
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

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
                redirect('index.php?page=dashboard');
            } else {
                 $error_message = 'Failed to create incident.';
            }
        }
        $view_to_include = 'create_incident';
        break;

    // --- CMDB ---
    case 'cmdb_list':
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

        // Load CI Types for dropdown
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
        $query = "SELECT * FROM problems WHERE tenant_id = :tenant_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'problem_list';
        break;

    case 'create_problem':
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = sanitize_input($_POST['title']);
            $description = sanitize_input($_POST['description']);
            $impact = sanitize_input($_POST['impact']);
            $urgency = sanitize_input($_POST['urgency']);
            $root_cause = sanitize_input($_POST['root_cause']);
            $problem_number = 'PRB-' . strtoupper(substr(md5(uniqid()), 0, 8));

             // Find Priority (Simplified reuse logic)
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

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

             // Find Priority (Simplified reuse logic)
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
        $query = "SELECT r.*, s.service_name FROM service_requests r
                  LEFT JOIN service_catalog s ON r.service_id = s.service_id
                  WHERE r.tenant_id = :tenant_id ORDER BY r.requested_date DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'request_list';
        break;

    case 'create_request':
        $auth->requireLogin();
        $user = $auth->getCurrentUser();

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

             // Find Priority (Simplified reuse logic)
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
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
        // Simple permission check (in a real app, this would be more robust)
        if ($user['role'] !== 'tenant_admin' && $user['role'] !== 'admin') {
            $error_message = 'Access Denied. Admins only.';
            $view_to_include = 'dashboard'; // fallback
            break;
        }

        $query = "SELECT * FROM users WHERE tenant_id = :tenant_id ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tenant_id', $user['tenant_id']);
        $stmt->execute();
        $users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $view_to_include = 'user_list';
        break;

    case 'create_user':
        $auth->requireLogin();
        $user = $auth->getCurrentUser();
        if ($user['role'] !== 'tenant_admin' && $user['role'] !== 'admin') {
             redirect('index.php?page=dashboard');
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
