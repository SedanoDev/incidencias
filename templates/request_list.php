<div class="welcome-section">
    <h1>Service Requests</h1>
    <p>View and manage service requests.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search requests...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_request" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New Request
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($requests)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No service requests found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Title</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($req['request_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($req['title']); ?></td>
                            <td><?php echo htmlspecialchars($req['service_name'] ?? 'General'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($req['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($req['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($req['requested_date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
