<div class="card">
    <h2>Update Incident: <?php echo htmlspecialchars($incident['incident_number']); ?></h2>
    <form action="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($incident['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <?php
                $statuses = ['new', 'assigned', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled'];
                foreach ($statuses as $st) {
                    $selected = ($incident['status'] === $st) ? 'selected' : '';
                    echo "<option value=\"$st\" $selected>" . ucfirst($st) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="assigned_to">Assign To</label>
            <select name="assigned_to" id="assigned_to">
                <option value="">-- Unassigned --</option>
                <?php foreach ($tech_users as $tech): ?>
                    <?php $sel = ($incident['assigned_to'] == $tech['user_id']) ? 'selected' : ''; ?>
                    <option value="<?php echo $tech['user_id']; ?>" <?php echo $sel; ?>>
                        <?php echo htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="urgency">Urgency</label>
            <select name="urgency" id="urgency">
                <?php
                $levels = ['low', 'medium', 'high', 'critical'];
                foreach ($levels as $lvl) {
                    $selected = ($incident['urgency'] === $lvl) ? 'selected' : '';
                    echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="impact">Impact</label>
            <select name="impact" id="impact">
                <?php
                foreach ($levels as $lvl) {
                    $selected = ($incident['impact'] === $lvl) ? 'selected' : '';
                    echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn">Update Incident</button>
        <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="btn" style="background: #777;">Cancel</a>
    </form>
</div>
