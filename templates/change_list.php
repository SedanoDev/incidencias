<div class="welcome-section">
    <h1>Changes</h1>
    <p>Manage infrastructure and service changes.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search changes...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_change" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New Change
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($changes)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No changes found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Change #</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Risk</th>
                        <th>Status</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($changes as $change): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($change['change_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($change['title']); ?></td>
                            <td><span class="badge badge-assigned" style="color: #4b5563; background: #e5e7eb; border-color: #d1d5db;"><?php echo htmlspecialchars($change['type_name']); ?></span></td>
                             <td>
                                <span class="badge badge-<?php echo strtolower($change['risk_level']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($change['risk_level'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($change['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($change['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($change['requested_date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
