<div class="card">
    <h2>Change Management</h2>
    <div style="margin-bottom: 15px;">
        <a href="index.php?page=create_change" class="btn">Request New Change</a>
    </div>

    <?php if (empty($changes)): ?>
        <p>No changes found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Change #</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Risk</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($changes as $change): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($change['change_number']); ?></td>
                        <td><?php echo htmlspecialchars($change['title']); ?></td>
                        <td><?php echo htmlspecialchars($change['type_name']); ?></td>
                         <td>
                            <span class="badge badge-<?php echo strtolower($change['risk_level']); ?>">
                                <?php echo htmlspecialchars(ucfirst($change['risk_level'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(ucfirst($change['status'])); ?></td>
                        <td><?php echo htmlspecialchars($change['requested_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
