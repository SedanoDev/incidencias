<div class="welcome-section">
    <h1>Create Change Request</h1>
    <p>Submit a request for change (RFC).</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_change" method="POST">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" required placeholder="Change summary">
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="change_type_id" class="form-label">Change Type</label>
                     <select name="change_type_id" id="change_type_id" class="form-control" required>
                        <option value="">-- Select Type --</option>
                        <?php foreach ($change_types as $type): ?>
                            <option value="<?php echo $type['change_type_id']; ?>">
                                <?php echo htmlspecialchars($type['type_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="risk_level" class="form-label">Risk Level</label>
                    <select name="risk_level" id="risk_level" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required placeholder="What is being changed?"></textarea>
            </div>

            <div class="form-group">
                <label for="reason_for_change" class="form-label">Reason for Change</label>
                <textarea name="reason_for_change" id="reason_for_change" class="form-control" rows="3" required placeholder="Why is this change needed?"></textarea>
            </div>

            <div class="form-group">
                <label for="backout_plan" class="form-label">Backout Plan</label>
                <textarea name="backout_plan" id="backout_plan" class="form-control" rows="3" required placeholder="How to revert if unsuccessful?"></textarea>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="urgency" class="form-label">Urgency</label>
                    <select name="urgency" id="urgency" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="impact" class="form-label">Impact</label>
                    <select name="impact" id="impact" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: var(--space-24);">
                <button type="submit" class="btn btn-primary">
                    Submit Request
                </button>
                <a href="index.php?page=change_list" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Change Management</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                Changes require approval before implementation. Please ensure the Risk Assessment is accurate.
            </p>
        </div>
    </div>
</div>
