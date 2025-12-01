<div class="card">
    <h2>Add Configuration Item (CI)</h2>
    <form action="index.php?page=create_ci" method="POST">
        <div class="form-group">
            <label for="ci_name">CI Name</label>
            <input type="text" name="ci_name" id="ci_name" required>
        </div>
        <div class="form-group">
            <label for="ci_type_id">CI Type</label>
            <select name="ci_type_id" id="ci_type_id" required>
                <option value="">-- Select Type --</option>
                <?php foreach ($ci_types as $type): ?>
                    <option value="<?php echo $type['ci_type_id']; ?>">
                        <?php echo htmlspecialchars($type['type_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="under_maintenance">Under Maintenance</option>
            </select>
        </div>
        <div class="form-group">
            <label for="criticality">Criticality</label>
            <select name="criticality" id="criticality">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>
        <button type="submit" class="btn">Create CI</button>
    </form>
</div>
