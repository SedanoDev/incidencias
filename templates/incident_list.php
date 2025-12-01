<div class="welcome-section">
    <h1>Incidents</h1>
    <p>Manage and track all reported incidents.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search incidents...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_incident" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New Incident
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($incidents)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No incidents found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <tr onclick="window.location='index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>'">
                            <td><strong><?php echo htmlspecialchars($incident['incident_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($incident['title']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($incident['impact']); ?>">
                                    <?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($incident['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $incident['status']))); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(date('M j, H:i', strtotime($incident['reported_date']))); ?></td>
                            <?php if ($_SESSION['role'] !== 'end_user'): ?>
                                <td><?php echo htmlspecialchars($incident['assigned_name'] ?? 'Unassigned'); ?></td>
                            <?php endif; ?>
                            <td onclick="event.stopPropagation()">
                                 <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="action-btn" title="View">
                                     <i class="fas fa-eye"></i>
                                 </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <!-- Pagination (Visual only for MVP) -->
    <div class="pagination">
        <button disabled><i class="fas fa-chevron-left"></i></button>
        <button class="active">1</button>
        <button><i class="fas fa-chevron-right"></i></button>
    </div>
</div>
