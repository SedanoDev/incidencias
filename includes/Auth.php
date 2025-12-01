<?php
// includes/Auth.php

class Auth {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login user
    public function login($username_or_email, $password, $tenant_id) {
        $query = "SELECT user_id, tenant_id, username, email, password_hash, role, first_name, last_name
                  FROM " . $this->table_name . "
                  WHERE (username = :username OR email = :email)
                  AND tenant_id = :tenant_id
                  AND is_active = 1
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        $username_or_email = htmlspecialchars(strip_tags($username_or_email));
        $tenant_id = intval($tenant_id);

        $stmt->bindParam(':username', $username_or_email);
        $stmt->bindParam(':email', $username_or_email);
        $stmt->bindParam(':tenant_id', $tenant_id);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $row['password_hash'])) {
                // Password is correct
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['tenant_id'] = $row['tenant_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['name'] = $row['first_name'] . ' ' . $row['last_name'];

                // Update last login
                $update_query = "UPDATE " . $this->table_name . " SET last_login_at = NOW() WHERE user_id = :user_id";
                $update_stmt = $this->conn->prepare($update_query);
                $update_stmt->bindParam(':user_id', $row['user_id']);
                $update_stmt->execute();

                return true;
            }
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'tenant_id' => $_SESSION['tenant_id'],
                'username' => $_SESSION['username'],
                'role' => $_SESSION['role'],
                'name' => $_SESSION['name']
            ];
        }
        return null;
    }

    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: index.php?page=login");
            exit;
        }
    }
}
