<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'Ticketing SaaS'; ?></title>
    <!-- Simple CSS for demonstration -->
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 95%; max-width: 1200px; margin: auto; overflow: hidden; padding: 20px; }
        header { background: #333; color: #fff; padding-top: 30px; min-height: 70px; border-bottom: #77aaff 3px solid; }
        header a { color: #fff; text-decoration: none; text-transform: uppercase; font-size: 14px; }
        header ul { padding: 0; margin: 0; list-style: none; overflow: hidden; }
        header li { float: left; display: inline; padding: 0 15px 0 15px; }
        header #branding { float: left; }
        header #branding h1 { margin: 0; font-size: 24px; }
        header nav { float: right; margin-top: 10px; }
        .card { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background: #333; color: #fff; padding: 8px 15px; cursor: pointer; text-decoration: none; border: 0; border-radius: 3px; font-size: 14px; }
        .btn:hover { background: #555; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        input[type="text"], input[type="email"], input[type="password"], textarea, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; color: #fff; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #28a745; }
        .alert-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #f4f4f4; }
        .badge { display: inline-block; padding: 3px 7px; font-size: 12px; font-weight: bold; line-height: 1; color: #fff; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 10px; }
        .badge-low { background-color: #28a745; }
        .badge-medium { background-color: #ffc107; color: #000; }
        .badge-high { background-color: #fd7e14; }
        .badge-critical { background-color: #dc3545; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><a href="index.php">ITIL Service Desk</a></h1>
            </div>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                            // Quick access to role (assuming Auth::hasPermission logic is mirrored or accessible)
                            $role = $_SESSION['role'] ?? 'end_user';
                        ?>
                        <li><a href="index.php?page=dashboard">Incidents</a></li>

                        <?php if ($role !== 'end_user'): ?>
                            <li><a href="index.php?page=problem_list">Problems</a></li>
                            <li><a href="index.php?page=change_list">Changes</a></li>
                        <?php endif; ?>

                        <li><a href="index.php?page=request_list">Requests</a></li>

                        <?php if ($role !== 'end_user'): ?>
                             <li><a href="index.php?page=cmdb_list">CMDB</a></li>
                        <?php endif; ?>

                        <?php if ($role === 'admin' || $role === 'tenant_admin' || $role === 'manager'): ?>
                            <li><a href="index.php?page=user_list">Users</a></li>
                        <?php endif; ?>

                        <li><a href="index.php?page=logout">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">Login</a></li>
                        <li><a href="index.php?page=register_tenant">Register Company</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <?php flash_message('msg_flash'); ?>
