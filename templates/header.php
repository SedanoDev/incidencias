<!DOCTYPE html>
<html lang="es">
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
    <div class="navbar">
        <div class="navbar-left">
            <a href="index.php" class="navbar-logo"><i class="fas fa-ticket-alt"></i>ITIL</a>
            <div class="company-name">Acme Corporation</div>
        </div>
        <div class="navbar-right">
            <div class="navbar-icon" onclick="document.getElementById('notificationDropdown').classList.toggle('show')">
                <i class="fas fa-bell"></i>
                <?php if (isset($unread_notifications_count) && $unread_notifications_count > 0): ?>
                    <span class="notification-badge"><?php echo $unread_notifications_count > 9 ? '9+' : $unread_notifications_count; ?></span>
                <?php endif; ?>

                <div class="dropdown-menu" id="notificationDropdown" style="width: 300px; right: -10px; top: 30px;">
                    <div style="padding: 12px; font-weight: 600; border-bottom: 1px solid #eee; font-size: 14px;">Notificaciones</div>
                    <?php if (empty($notifications)): ?>
                        <div class="dropdown-item" style="cursor: default; color: #777; font-size: 13px;">No hay notificaciones nuevas</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <div class="dropdown-item" style="flex-direction: column; align-items: flex-start; border-bottom: 1px solid #f0f0f0;">
                                <div style="font-weight: 600; font-size: 13px; margin-bottom: 4px;"><?php echo htmlspecialchars($notif['subject']); ?></div>
                                <div style="font-size: 12px; color: #666; line-height: 1.4;"><?php echo htmlspecialchars(substr($notif['message'], 0, 60)) . '...'; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="navbar-icon">
                <i class="fas fa-cog"></i>
            </div>
            <div class="user-profile" onclick="document.getElementById('userDropdown').classList.toggle('show')">
                <div class="user-avatar"><?php echo strtoupper(substr($user_name, 0, 2)); ?></div>
                <span style="font-size: 14px; margin-left: 8px; font-weight: 500;"><?php echo htmlspecialchars($user_name); ?></span>
                <i class="fas fa-chevron-down" style="font-size: 12px; margin-left: 8px; color: #666;"></i>

                <div class="dropdown-menu" id="userDropdown">
                    <a href="index.php?page=logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <ul class="sidebar-menu">
            <?php
            $nav_items = [
                ['page' => 'dashboard', 'icon' => 'fa-home', 'label' => 'Dashboard', 'roles' => ['all']],
                ['page' => 'incidents', 'icon' => 'fa-exclamation-circle', 'label' => 'Incidentes', 'roles' => ['all']],
            ];

            if ($role !== 'end_user') {
                $nav_items[] = ['page' => 'problem_list', 'icon' => 'fa-bug', 'label' => 'Problemas', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
                $nav_items[] = ['page' => 'change_list', 'icon' => 'fa-exchange-alt', 'label' => 'Cambios', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
            }

            $nav_items[] = ['page' => 'request_list', 'icon' => 'fa-file-alt', 'label' => 'Solicitudes', 'roles' => ['all']];

            if ($role !== 'end_user') {
                $nav_items[] = ['page' => 'cmdb_list', 'icon' => 'fa-database', 'label' => 'CMDB', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
                $nav_items[] = ['page' => 'reports', 'icon' => 'fa-chart-bar', 'label' => 'Reportes', 'roles' => ['technician', 'manager', 'admin', 'tenant_admin']];
            }

            $nav_items[] = ['page' => 'settings', 'icon' => 'fa-sliders-h', 'label' => 'Configuración', 'roles' => ['all']]; // Settings for everyone (profile) or role restricted inside

            $current_page = $_GET['page'] ?? 'dashboard';
            // Map list pages to sidebar items if needed
            if ($current_page === 'create_incident' || $current_page === 'view_incident') $current_page = 'incidents';

            foreach ($nav_items as $item) {
                $active = ($current_page === $item['page']) ? 'active' : '';
                echo "<li class=\"sidebar-item\">";
                echo "<a href=\"index.php?page={$item['page']}\" class=\"sidebar-link $active\">";
                echo "<i class=\"fas {$item['icon']}\"></i>";
                echo "<span>{$item['label']}</span>";
                echo "</a>";
                echo "</li>";
            }
            ?>
        </ul>
    </div>

    <div class="main-content">
        <?php flash_message('msg_flash'); ?>
<?php else: ?>
    <!-- Not Logged In Layout -->
    <!-- Content will be rendered directly by login/register pages -->
<?php endif; ?>
