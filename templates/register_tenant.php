<div class="card">
    <h2>Register Organization</h2>
    <form action="index.php?page=register_tenant" method="POST">
        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" name="company_name" id="company_name" required>
        </div>
        <div class="form-group">
            <label for="tenant_code">Organization Code (Unique ID)</label>
            <input type="text" name="tenant_code" id="tenant_code" required placeholder="e.g. ACME">
        </div>
        <div class="form-group">
            <label for="subdomain">Subdomain</label>
            <input type="text" name="subdomain" id="subdomain" required placeholder="acme">
        </div>
        <div class="form-group">
            <label for="contact_email">Contact Email</label>
            <input type="email" name="contact_email" id="contact_email" required>
        </div>
        <div class="form-group">
            <label for="admin_username">Admin Username</label>
            <input type="text" name="admin_username" id="admin_username" required>
        </div>
        <div class="form-group">
            <label for="admin_password">Admin Password</label>
            <input type="password" name="admin_password" id="admin_password" required>
        </div>
        <div class="form-group">
            <label for="subscription_plan">Plan</label>
            <select name="subscription_plan" id="subscription_plan">
                <option value="free">Free</option>
                <option value="basic">Basic</option>
                <option value="professional">Professional</option>
            </select>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
</div>
