<?php
// config/config.php

define('APP_NAME', 'ITIL Ticketing SaaS');
define('APP_URL', 'http://localhost:8000'); // Adjust as needed
define('DEBUG', true);

// Set error reporting based on debug mode
if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

session_start();
