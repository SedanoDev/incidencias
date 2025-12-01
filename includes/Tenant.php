<?php
// includes/Tenant.php

class Tenant {
    private $conn;
    private $table_name = "tenants";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTenantByCode($code) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE tenant_code = :code LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTenantBySubdomain($subdomain) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE subdomain = :subdomain LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subdomain', $subdomain);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTenant($data) {
        // Basic validation should be done before calling this

        $query = "INSERT INTO " . $this->table_name . "
                (tenant_code, company_name, subdomain, contact_email, subscription_plan)
                VALUES (:tenant_code, :company_name, :subdomain, :contact_email, :subscription_plan)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':tenant_code', $data['tenant_code']);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->bindParam(':subdomain', $data['subdomain']);
        $stmt->bindParam(':contact_email', $data['contact_email']);
        $stmt->bindParam(':subscription_plan', $data['subscription_plan']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
}
