<div class="welcome-section">
    <h1>Create Configuration Item</h1>
    <p>Register a new asset or service in the CMDB.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_ci" method="POST">
            <div class="form-group">
                <label for="ci_name" class="form-label">CI Name</label>
                <input type="text" name="ci_name" id="ci_name" class="form-input" required placeholder="e.g. Server-01">
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="ci_type_id" class="form-label">Type</label>
                    <select name="ci_type_id" id="ci_type_id" class="form-input" required>
                        <option value="">-- Select Type --</option>
                        <?php foreach ($ci_types as $type): ?>
                            <option value="<?php echo $type['ci_type_id']; ?>">
                                <?php echo htmlspecialchars($type['type_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="under_maintenance">Under Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" rows="3" placeholder="Specs, location, details..."></textarea>
            </div>

            <div class="form-group">
                <label for="criticality" class="form-label">Criticality</label>
                <select name="criticality" id="criticality" class="form-input">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <div style="margin-top: var(--space-24);">
                <button type="submit" class="btn btn-primary">
                    Create CI
                </button>
                <a href="index.php?page=cmdb_list" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>CMDB Guide</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                Configuration Items (CIs) are assets, services, or components that need to be managed. Ensure naming conventions are followed.
            </p>
        </div>
    </div>
</div>
