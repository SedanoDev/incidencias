<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'Ticketing SaaS'; ?></title>
    <!-- Simple CSS for demonstration -->
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { width: 80%; margin: auto; overflow: hidden; padding: 20px; }
        header { background: #333; color: #fff; padding-top: 30px; min-height: 70px; border-bottom: #77aaff 3px solid; }
        header a { color: #fff; text-decoration: none; text-transform: uppercase; font-size: 16px; }
        header ul { padding: 0; margin: 0; list-style: none; overflow: hidden; }
        header li { float: left; display: inline; padding: 0 20px 0 20px; }
        header #branding { float: left; }
        header #branding h1 { margin: 0; }
        header nav { float: right; margin-top: 10px; }
        .card { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background: #333; color: #fff; padding: 10px 20px; cursor: pointer; text-decoration: none; border: 0; }
        .btn:hover { background: #555; }
        input[type="text"], input[type="email"], input[type="password"], textarea, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; color: #fff; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #28a745; }
        .alert-danger { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        table th { background: #f4f4f4; }
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
                        <li><a href="index.php?page=dashboard">Dashboard</a></li>
                        <li><a href="index.php?page=create_incident">New Incident</a></li>
                        <li><a href="index.php?page=logout">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
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
