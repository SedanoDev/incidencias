<div class="card">
    <h2>Service Requests</h2>
    <div style="margin-bottom: 15px;">
        <a href="index.php?page=create_request" class="btn">New Service Request</a>
    </div>

    <?php if (empty($requests)): ?>
        <p>No service requests found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Request #</th>
                    <th>Title</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($req['request_number']); ?></td>
                        <td><?php echo htmlspecialchars($req['title']); ?></td>
                        <td><?php echo htmlspecialchars($req['service_name'] ?? 'General'); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($req['status'])); ?></td>
                        <td><?php echo htmlspecialchars($req['requested_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
