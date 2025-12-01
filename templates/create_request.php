<div class="card">
    <h2>New Service Request</h2>
    <form action="index.php?page=create_request" method="POST">
        <div class="form-group">
            <label for="service_id">Service (Optional)</label>
            <select name="service_id" id="service_id">
                <option value="">-- General Request --</option>
                <?php foreach ($services as $svc): ?>
                    <option value="<?php echo $svc['service_id']; ?>">
                        <?php echo htmlspecialchars($svc['service_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="5" required></textarea>
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
        <button type="submit" class="btn">Submit Request</button>
    </form>
</div>
