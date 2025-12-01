<div class="card">
    <h2>Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>

    <div style="margin-bottom: 20px;">
        <a href="index.php?page=create_incident" class="btn">Create New Incident</a>
    </div>

    <h3><?php echo ($_SESSION['role'] === 'end_user') ? 'My Active Incidents' : 'All Active Incidents'; ?></h3>

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
                    <th>Reported</th>
                    <?php if ($_SESSION['role'] !== 'end_user'): ?>
                        <th>Assigned To</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $incident): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($incident['incident_number']); ?></td>
                        <td><?php echo htmlspecialchars($incident['title']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($incident['impact']); ?>">
                                <?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(ucfirst($incident['status'])); ?></td>
                        <td><?php echo htmlspecialchars($incident['reported_date']); ?></td>
                        <?php if ($_SESSION['role'] !== 'end_user'): ?>
                            <td><?php echo htmlspecialchars($incident['assigned_name'] ?? 'Unassigned'); ?></td>
                        <?php endif; ?>
                        <td>
                             <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="btn btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
