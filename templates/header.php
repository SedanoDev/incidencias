<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'ITIL Service Desk'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
// Check if user is logged in for Layout Logic
$is_logged_in = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'end_user';
$user_name = $_SESSION['name'] ?? 'Guest';
?>

<?php if ($is_logged_in): ?>
<div class="app-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="sidebar-title">ServiceDesk</div>
        </div>
        <nav class="sidebar-nav">
            <?php
            // Navigation Items based on Role
            $nav_items = [
                ['page' => 'dashboard', 'icon' => 'fa-th-large', 'label' => 'Dashboard', 'roles' => ['all']],
                ['page' => 'incidents', 'icon' => 'fa-exclamation-circle', 'label' => 'Incidentes', 'roles' => ['all']],
            ];

            if ($role !== 'end_user') {
                $nav_items[] = ['page' => 'problem_list', 'icon' => 'fa-bug', 'label' => 'Problemas', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
                $nav_items[] = ['page' => 'change_list', 'icon' => 'fa-random', 'label' => 'Cambios', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
            }

            $nav_items[] = ['page' => 'request_list', 'icon' => 'fa-clipboard-list', 'label' => 'Solicitudes', 'roles' => ['all']];

            if ($role !== 'end_user') {
                $nav_items[] = ['page' => 'cmdb_list', 'icon' => 'fa-database', 'label' => 'CMDB', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
            }

            if (in_array($role, ['admin', 'tenant_admin', 'manager'])) {
                $nav_items[] = ['page' => 'user_list', 'icon' => 'fa-users', 'label' => 'Usuarios', 'roles' => ['admin', 'tenant_admin', 'manager']];
            }

            $current_page = $_GET['page'] ?? 'dashboard';

            foreach ($nav_items as $item) {
                $active = ($current_page === $item['page']) ? 'active' : '';
                echo "<a href=\"index.php?page={$item['page']}\" class=\"nav-item $active\">";
                echo "<i class=\"fas {$item['icon']}\"></i>";
                echo "<span>{$item['label']}</span>";
                echo "</a>";
            }
            ?>
        </nav>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="navbar-left">
                <h2 style="font-size: var(--font-size-xl); font-weight: var(--font-weight-semibold); color: var(--color-text);">
                    Welcome, <?php echo htmlspecialchars($user_name); ?>
                </h2>
            </div>
            <div class="navbar-right">
                <div class="navbar-icon" onclick="document.getElementById('notificationDropdown').classList.toggle('show')">
                    <i class="fas fa-bell"></i>
                    <?php if ($unread_notifications_count > 0): ?>
                        <span class="badge"><?php echo $unread_notifications_count > 9 ? '9+' : $unread_notifications_count; ?></span>
                    <?php endif; ?>

                    <div class="dropdown-menu" id="notificationDropdown" style="width: 300px;">
                        <div style="padding: 10px; font-weight: bold; border-bottom: 1px solid #eee;">Notifications</div>
                        <?php if (empty($notifications)): ?>
                            <div class="dropdown-item" style="cursor: default; color: #777;">No new notifications</div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="dropdown-item" style="flex-direction: column; align-items: flex-start; border-bottom: 1px solid #f0f0f0;">
                                    <div style="font-weight: 500; font-size: 13px;"><?php echo htmlspecialchars($notif['subject']); ?></div>
                                    <div style="font-size: 11px; color: #777; margin-top: 2px;"><?php echo htmlspecialchars(substr($notif['message'], 0, 50)) . '...'; ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="user-menu" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                    <div class="user-avatar"><?php echo strtoupper(substr($user_name, 0, 2)); ?></div>
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $role))); ?></div>
                    </div>
                    <i class="fas fa-chevron-down" style="color: var(--color-text-secondary); font-size: var(--font-size-xs);"></i>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="index.php?page=logout" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <?php flash_message('msg_flash'); ?>
<?php else: ?>
    <!-- Not Logged In Layout handled by individual pages or simple wrapper -->
    <div class="container" style="padding-top: 20px;">
        <?php flash_message('msg_flash'); ?>
<?php endif; ?>
