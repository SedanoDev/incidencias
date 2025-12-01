<div class="welcome-section">
    <h1>New Service Request</h1>
    <p>Request a new service from the catalog.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_request" method="POST">
            <div class="form-group">
                <label for="service_id" class="form-label">Service</label>
                <select name="service_id" id="service_id" class="form-input">
                    <option value="">-- General Request --</option>
                    <?php foreach ($services as $svc): ?>
                        <option value="<?php echo $svc['service_id']; ?>">
                            <?php echo htmlspecialchars($svc['service_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" required placeholder="Short summary">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" rows="5" required placeholder="Please describe your request..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label for="urgency" class="form-label">Urgency</label>
                    <select name="urgency" id="urgency" class="form-input">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="impact" class="form-label">Impact</label>
                    <select name="impact" id="impact" class="form-input">
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
                <a href="index.php?page=request_list" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Service Catalog</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                Select a service from the catalog for faster processing. General requests may require triage.
            </p>
        </div>
    </div>
</div>
