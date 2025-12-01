<div class="welcome-section">
    <h1>Create New Incident</h1>
    <p>Report a new issue or incident.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_incident" method="POST">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input" required placeholder="Brief description of the issue">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" rows="5" required placeholder="Detailed description..."></textarea>
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
                    Submit Incident
                </button>
                <a href="index.php?page=dashboard" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Help</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin-bottom: var(--space-12);">
                Please provide as much detail as possible to help us resolve your issue quickly.
            </p>
            <div class="info-row">
                <span class="info-label">Urgency Guide</span>
            </div>
            <p style="font-size: var(--font-size-xs); color: var(--color-text-secondary);">
                <strong>Critical:</strong> Business stopped.<br>
                <strong>High:</strong> Major feature broken.<br>
                <strong>Medium:</strong> Performance issue.<br>
                <strong>Low:</strong> Minor annoyance.
            </p>
        </div>
    </div>
</div>
