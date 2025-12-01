<?php
// includes/functions.php

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // Removed htmlspecialchars to avoid double encoding when storing in DB
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

function create_notification($db, $tenant_id, $user_id, $ticket_type, $ticket_id, $type, $subject, $message) {
    if (!$user_id) return false;

    $query = "INSERT INTO notifications (tenant_id, user_id, ticket_type, ticket_id, notification_type, subject, message)
              VALUES (:tenant_id, :user_id, :ticket_type, :ticket_id, :type, :subject, :message)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':tenant_id', $tenant_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':ticket_type', $ticket_type);
    $stmt->bindParam(':ticket_id', $ticket_id);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    return $stmt->execute();
}
