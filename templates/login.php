<div class="card">
    <h2>Login</h2>
    <form action="index.php?page=login" method="POST">
        <div class="form-group">
            <label for="tenant_code">Organization Code</label>
            <input type="text" name="tenant_code" id="tenant_code" required>
        </div>
        <div class="form-group">
            <label for="email">Email or Username</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>
