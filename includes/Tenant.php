<?php
// includes/Tenant.php

class Tenant {
    private $conn;
    private $table_name = "tenants";

    // Plan Definitions based on user requirements
    private $plans = [
        'free' => [
            'max_users' => 5,
            'max_incidents' => 100,
            'storage_gb' => 5,
            'features' => ['incidents', 'problems_limited', 'requests_basic']
        ],
        'basic' => [
            'max_users' => 25,
            'max_incidents' => 500,
            'storage_gb' => 50,
            'features' => ['incidents', 'problems', 'requests', 'changes_basic', 'cmdb_basic']
        ],
        'professional' => [
            'max_users' => 100,
            'max_incidents' => 2000,
            'storage_gb' => 200,
            'features' => ['incidents', 'problems', 'requests', 'changes', 'cmdb', 'sla', 'escalations']
        ],
        'enterprise' => [
            'max_users' => 999999, // Unlimited
            'max_incidents' => 999999, // Unlimited
            'storage_gb' => 2048,
            'features' => ['all']
        ]
    ];

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

    public function getTenantById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE tenant_id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
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

    // --- Limit Checks ---

    public function checkUsageLimit($tenant_id, $type) {
        $tenant = $this->getTenantById($tenant_id);
        if (!$tenant) return false;

        $plan = $tenant['subscription_plan'];
        $limits = $this->plans[$plan] ?? $this->plans['free']; // Default to free if unknown

        if ($type === 'users') {
            // Count current users
            $query = "SELECT COUNT(*) as count FROM users WHERE tenant_id = :tenant_id AND is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tenant_id', $tenant_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['count'] < $limits['max_users'];
        }

        if ($type === 'incidents') {
            // Count incidents this month
            // Assuming tenant_usage table is populated or we count directly from incidents table
            // For MVP, counting directly from incidents table for current month
            $query = "SELECT COUNT(*) as count FROM incidents WHERE tenant_id = :tenant_id AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tenant_id', $tenant_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['count'] < $limits['max_incidents'];
        }

        return true;
    }

    public function hasFeature($tenant_id, $feature) {
        $tenant = $this->getTenantById($tenant_id);
        if (!$tenant) return false;

        $plan = $tenant['subscription_plan'];
        $limits = $this->plans[$plan] ?? $this->plans['free'];

        if (in_array('all', $limits['features'])) return true;

        // Handle specific feature checks or basic presence
        return in_array($feature, $limits['features']);
    }

    public function getPlanDetails($plan_name) {
         return $this->plans[$plan_name] ?? $this->plans['free'];
    }
}
