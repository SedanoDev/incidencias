<div class="login-page">
    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <h1>ITIL ServiceDesk</h1>
            <p>Acme Corporation</p>
        </div>
        <form action="index.php?page=login" method="POST">
            <div class="form-group">
                <label for="tenant_code" class="form-label">Organization Code</label>
                <input type="text" name="tenant_code" id="tenant_code" class="form-input" required placeholder="e.g. ACME">
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email or Username</label>
                <input type="text" name="email" id="email" class="form-input" required placeholder="user@example.com">
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
            </div>
            <div class="form-check">
                <input type="checkbox" id="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>
        <div class="login-footer">
            <a href="#">Forgot password?</a>
            <p class="mt-16">Don't have an account? <a href="index.php?page=register_tenant">Register Company</a></p>
        </div>
    </div>
</div>
