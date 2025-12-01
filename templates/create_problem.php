<div class="welcome-section">
    <h1>Create New Problem</h1>
    <p>Document a new problem for root cause analysis.</p>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <form action="index.php?page=create_problem" method="POST">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" required placeholder="Problem summary">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required placeholder="Detailed description..."></textarea>
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

            <div class="form-group">
                <label for="root_cause" class="form-label">Root Cause (Initial Analysis)</label>
                <textarea name="root_cause" id="root_cause" class="form-control" rows="3" placeholder="If known..."></textarea>
            </div>

            <div style="margin-top: var(--space-24);">
                <button type="submit" class="btn btn-primary">
                    Create Problem
                </button>
                <a href="index.php?page=problem_list" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Problem Management</h3>
            <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary);">
                Problems are the underlying cause of one or more Incidents. The goal is to identify the root cause and propose a permanent fix.
            </p>
        </div>
    </div>
</div>
