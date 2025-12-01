<div class="card">
    <h2>Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>

    <h3>Active Incidents</h3>
    <?php if (empty($incidents)): ?>
        <p>No active incidents found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Reported Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $incident): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($incident['incident_number']); ?></td>
                        <td><?php echo htmlspecialchars($incident['title']); ?></td>
                        <td><?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($incident['status'])); ?></td>
                        <td><?php echo htmlspecialchars($incident['reported_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php?page=create_incident" class="btn">Create New Incident</a>
</div>
