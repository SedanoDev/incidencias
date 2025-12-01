<div class="card">
    <h2>Request New Change</h2>
    <form action="index.php?page=create_change" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div class="form-group">
            <label for="change_type_id">Change Type</label>
             <select name="change_type_id" id="change_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($change_types as $type): ?>
                    <option value="<?php echo $type['change_type_id']; ?>">
                        <?php echo htmlspecialchars($type['type_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="reason_for_change">Reason for Change</label>
            <textarea name="reason_for_change" id="reason_for_change" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="backout_plan">Backout Plan</label>
            <textarea name="backout_plan" id="backout_plan" rows="3" required></textarea>
        </div>
         <div class="form-group">
            <label for="risk_level">Risk Level</label>
            <select name="risk_level" id="risk_level">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>
        <div class="form-group">
            <label for="urgency">Urgency</label>
            <select name="urgency" id="urgency">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>
        <div class="form-group">
            <label for="impact">Impact</label>
            <select name="impact" id="impact">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>
        <button type="submit" class="btn">Submit Change Request</button>
    </form>
</div>
