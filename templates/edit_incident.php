<div class="welcome-section">
    <h1>Update Incident</h1>
    <p>Modify incident details, status, or assignment.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" method="POST">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" value="<?php echo htmlspecialchars($incident['title']); ?>" required>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input">
                        <?php
                        $statuses = ['new', 'assigned', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled'];
                        foreach ($statuses as $st) {
                            $selected = ($incident['status'] === $st) ? 'selected' : '';
                            echo "<option value=\"$st\" $selected>" . ucfirst($st) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="assigned_to" class="form-label">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="form-input">
                        <option value="">-- Unassigned --</option>
                        <?php foreach ($tech_users as $tech): ?>
                            <?php $sel = ($incident['assigned_to'] == $tech['user_id']) ? 'selected' : ''; ?>
                            <option value="<?php echo $tech['user_id']; ?>" <?php echo $sel; ?>>
                                <?php echo htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="urgency" class="form-label">Urgency</label>
                    <select name="urgency" id="urgency" class="form-input">
                        <?php
                        $levels = ['low', 'medium', 'high', 'critical'];
                        foreach ($levels as $lvl) {
                            $selected = ($incident['urgency'] === $lvl) ? 'selected' : '';
                            echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="impact" class="form-label">Impact</label>
                    <select name="impact" id="impact" class="form-input">
                        <?php
                        foreach ($levels as $lvl) {
                            $selected = ($incident['impact'] === $lvl) ? 'selected' : '';
                            echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div style="margin-top: var(--space-24);">
                <button type="submit" class="btn btn-primary">
                    Update Incident
                </button>
                <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Update Guide</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                Updating status to "Resolved" will notify the user. Changing impact/urgency will recalculate priority.
            </p>
        </div>
    </div>
</div>
