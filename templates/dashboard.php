<div class="welcome-section">
    <h1>Dashboard</h1>
    <p><?php echo date('l, j F Y'); ?></p>
</div>

<!-- KPI Grid (Mock Data for Visuals, ideally populated from DB) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Active Incidents</span>
            <div class="kpi-icon blue">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
        <div class="kpi-value"><?php echo count($incidents); ?></div>
        <div class="kpi-trend positive">
            <i class="fas fa-arrow-up"></i>
            <span>Active</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">SLA Compliance</span>
            <div class="kpi-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="kpi-value">94%</div>
        <div class="kpi-trend positive">
            <i class="fas fa-check-circle"></i>
            <span>On Track</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Open Requests</span>
            <div class="kpi-icon green">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
        <div class="kpi-value">12</div>
        <div class="kpi-trend negative">
            <i class="fas fa-arrow-down"></i>
            <span>Pending</span>
        </div>
    </div>

     <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Team Satisfaction</span>
            <div class="kpi-icon purple">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="kpi-value">4.8/5</div>
        <div class="kpi-trend positive">
            <i class="fas fa-arrow-up"></i>
            <span>Stable</span>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="index.php?page=create_incident" class="quick-action-card">
        <div class="quick-action-icon" style="background: var(--color-bg-4); color: #ef4444;">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h3>New Incident</h3>
        <p>Report an issue</p>
    </a>
    <a href="index.php?page=create_request" class="quick-action-card">
        <div class="quick-action-icon" style="background: var(--color-bg-1); color: #3b82f6;">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h3>New Request</h3>
        <p>Request service</p>
    </a>
    <?php if ($_SESSION['role'] !== 'end_user'): ?>
    <a href="index.php?page=create_change" class="quick-action-card">
        <div class="quick-action-icon" style="background: var(--color-bg-6); color: #f97316;">
            <i class="fas fa-random"></i>
        </div>
        <h3>New Change</h3>
        <p>Plan a change</p>
    </a>
    <?php endif; ?>
</div>

<!-- Recent Incidents Table -->
<div class="table-card">
    <div class="table-header">
        <h2 class="table-title"><?php echo ($_SESSION['role'] === 'end_user') ? 'My Recent Incidents' : 'Recent Incidents'; ?></h2>
        <?php if (!empty($incidents)): ?>
            <!-- Optional: Link to full list -->
        <?php endif; ?>
    </div>

    <div class="table-container">
        <?php if (empty($incidents)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No active incidents found.</p>
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
</div>
