<div class="card">
    <h2>User Management</h2>
    <div style="margin-bottom: 15px;">
        <a href="index.php?page=create_user" class="btn">Create New User</a>
    </div>

    <?php if (empty($users_list)): ?>
        <p>No users found.</p>
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
                        <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $u['role']))); ?></td>
                        <td>
                             <span class="badge <?php echo $u['is_active'] ? 'badge-low' : 'badge-critical'; ?>">
                                <?php echo $u['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
