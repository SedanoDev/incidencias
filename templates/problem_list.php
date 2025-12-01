<div class="card">
    <h2>Problem Management</h2>
    <div style="margin-bottom: 15px;">
        <a href="index.php?page=create_problem" class="btn">Record New Problem</a>
    </div>

    <?php if (empty($problems)): ?>
        <p>No problems found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Problem #</th>
                    <th>Title</th>
                    <th>Impact</th>
                    <th>Status</th>
                    <th>Identified Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($problems as $problem): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($problem['problem_number']); ?></td>
                        <td><?php echo htmlspecialchars($problem['title']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($problem['impact']); ?>">
                                <?php echo htmlspecialchars(ucfirst($problem['impact'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(ucfirst($problem['status'])); ?></td>
                        <td><?php echo htmlspecialchars($problem['identified_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
