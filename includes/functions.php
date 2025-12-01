<?php
// includes/functions.php

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // Removed htmlspecialchars to avoid double encoding when storing in DB
    // We rely on PDO prepared statements for SQL injection prevention
    // and htmlspecialchars on output for XSS prevention.
    return $data;
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function flash_message($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : 'success';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}
