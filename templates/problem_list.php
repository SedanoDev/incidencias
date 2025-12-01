<div class="welcome-section">
    <h1>Problems</h1>
    <p>Track root cause analysis and known errors.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search problems...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_problem" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New Problem
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($problems)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No problems found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Problem #</th>
                        <th>Title</th>
                        <th>Impact</th>
                        <th>Status</th>
                        <th>Identified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($problems as $problem): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($problem['problem_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($problem['title']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($problem['impact']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($problem['impact'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($problem['status']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($problem['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($problem['identified_date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
