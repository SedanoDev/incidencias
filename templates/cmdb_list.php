<div class="card">
    <h2>Configuration Management Database (CMDB)</h2>
    <div style="margin-bottom: 15px;">
        <a href="index.php?page=create_ci" class="btn">Add New CI</a>
    </div>

    <?php if (empty($cis)): ?>
        <p>No configuration items found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>CI Number</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Criticality</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cis as $ci): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ci['ci_number']); ?></td>
                        <td><?php echo htmlspecialchars($ci['ci_name']); ?></td>
                        <td><?php echo htmlspecialchars($ci['type_name']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($ci['status'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($ci['criticality']); ?>">
                                <?php echo htmlspecialchars(ucfirst($ci['criticality'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($ci['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
