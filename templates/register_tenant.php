<div class="login-page">
    <div class="login-card" style="max-width: 500px;">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-building"></i>
            </div>
            <h1>Register Organization</h1>
            <p>Start your ITIL Service Desk journey</p>
        </div>
        <form action="index.php?page=register_tenant" method="POST">
            <div class="form-group">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tenant_code" class="form-label">Organization Code (Unique ID)</label>
                <input type="text" name="tenant_code" id="tenant_code" class="form-control" required placeholder="e.g. ACME">
            </div>
            <div class="form-group">
                <label for="subdomain" class="form-label">Subdomain</label>
                <input type="text" name="subdomain" id="subdomain" class="form-control" required placeholder="acme">
            </div>
            <div class="form-group">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_username" class="form-label">Admin Username</label>
                <input type="text" name="admin_username" id="admin_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_password" class="form-label">Admin Password</label>
                <input type="password" name="admin_password" id="admin_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="subscription_plan" class="form-label">Plan</label>
                <select name="subscription_plan" id="subscription_plan" class="form-control">
                    <option value="free">Free (Trial)</option>
                    <option value="basic">Basic</option>
                    <option value="professional">Professional</option>
                    <option value="enterprise">Enterprise</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                Register
            </button>
        </form>
         <div class="login-footer">
            <p class="mt-16">Already have an account? <a href="index.php?page=login">Login</a></p>
        </div>
    </div>
</div>
