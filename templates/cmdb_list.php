<div class="welcome-section">
    <h1>Configuration Items (CMDB)</h1>
    <p>Manage hardware, software, and services.</p>
</div>

<div class="toolbar">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search CIs...">
        </div>
    </div>
    <div class="toolbar-right">
        <a href="index.php?page=create_ci" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i>
            New CI
        </a>
    </div>
</div>

<div class="cmdb-layout">
    <!-- Simple category list acting as tree sidebar -->
    <div class="cmdb-tree">
        <h3 style="font-size: var(--font-size-base); font-weight: var(--font-weight-semibold); margin-bottom: var(--space-16);">Categories</h3>
        <div class="tree-item active"><i class="fas fa-server"></i> All Items</div>
        <div class="tree-item"><i class="fas fa-laptop"></i> Hardware</div>
        <div class="tree-item"><i class="fas fa-desktop"></i> Software</div>
    </div>

    <div>
        <div class="table-card">
            <div class="table-container">
                <?php if (empty($cis)): ?>
                    <p style="padding: 20px; color: var(--color-text-secondary);">No configuration items found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>CI Number</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Criticality</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cis as $ci): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($ci['ci_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($ci['ci_name']); ?></td>
                                    <td><?php echo htmlspecialchars($ci['type_name']); ?></td>
                                    <td><span class="badge badge-assigned" style="background-color: #dbeafe; color: #1e40af; border: none;"><?php echo htmlspecialchars(ucfirst($ci['status'])); ?></span></td>
                                    <td>
                                        <span class="badge badge-<?php echo strtolower($ci['criticality']); ?>">
                                            <?php echo htmlspecialchars(ucfirst($ci['criticality'])); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
