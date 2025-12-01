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
$incidents = []; // For dashboard

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

        // Fetch incidents for this tenant
        // In a real app, we would have an Incident class
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

            // Generate Incident Number (Simple Random for now)
            $incident_number = 'INC-' . strtoupper(substr(md5(time()), 0, 6));

            // Find Priority (Simplification: assuming a default mapping or simple lookup)
            // Ideally we query the priority matrix based on impact and urgency
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
