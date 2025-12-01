<div class="welcome-section">
    <h1>Users</h1>
    <p>Manage system users and roles.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search users...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_user" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            Create User
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($users_list)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No users found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users_list as $u): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><span class="badge badge-new" style="background-color: #f3f4f6; color: #374151; border: none;"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $u['role']))); ?></span></td>
                            <td>
                                 <span class="badge <?php echo $u['is_active'] ? 'badge-in_progress' : 'badge-critical'; ?>" style="border: none;">
                                    <?php echo $u['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
