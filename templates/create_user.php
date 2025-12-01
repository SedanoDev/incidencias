<div class="welcome-section">
    <h1>Create New User</h1>
    <p>Add a new user to the organization.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_user" method="POST">
            <div class="form-row">
                <div class="form-field">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-input" required>
                </div>
                <div class="form-field">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-input" required>
                </div>
                <div class="form-field">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" required>
                </div>
                <div class="form-field">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-input">
                        <option value="end_user">End User</option>
                        <option value="technician">Technician</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: var(--space-24);">
                <button type="submit" class="btn btn-primary">
                    Create User
                </button>
                <a href="index.php?page=user_list" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Roles</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin-bottom: var(--space-12);">
                <strong>End User:</strong> Can only create and view own tickets.<br>
                <strong>Technician:</strong> Can manage tickets.<br>
                <strong>Admin:</strong> Full access including user management.
            </p>
        </div>
    </div>
</div>
