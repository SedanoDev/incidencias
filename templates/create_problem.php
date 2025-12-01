<div class="card">
    <h2>Record New Problem</h2>
    <form action="index.php?page=create_problem" method="POST">
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
        <div class="form-group">
            <label for="root_cause">Root Cause (if known)</label>
            <textarea name="root_cause" id="root_cause" rows="3"></textarea>
        </div>
        <button type="submit" class="btn">Submit Problem</button>
    </form>
</div>
