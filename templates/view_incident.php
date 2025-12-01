<div class="card">
    <div style="float: right;">
        <a href="index.php?page=dashboard" class="btn btn-sm">Back to Dashboard</a>
        <?php if ($_SESSION['role'] !== 'end_user'): ?>
            <a href="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" class="btn btn-sm" style="background-color: #007bff;">Edit Incident</a>
        <?php endif; ?>
    </div>
    <h2>Incident: <?php echo htmlspecialchars($incident['incident_number']); ?></h2>

    <div class="grid-container">
        <div>
            <strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($incident['status'])); ?><br>
            <strong>Priority:</strong> <?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?><br>
            <strong>Reported By:</strong> <?php echo htmlspecialchars($incident['reporter_name']); ?><br>
            <strong>Created:</strong> <?php echo htmlspecialchars($incident['reported_date']); ?>
        </div>
        <div>
            <strong>Assigned To:</strong> <?php echo htmlspecialchars($incident['assigned_name'] ?? 'Unassigned'); ?><br>
            <strong>Impact:</strong> <?php echo htmlspecialchars(ucfirst($incident['impact'])); ?><br>
            <strong>Urgency:</strong> <?php echo htmlspecialchars(ucfirst($incident['urgency'])); ?>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <h3><?php echo htmlspecialchars($incident['title']); ?></h3>
        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
            <?php echo nl2br(htmlspecialchars($incident['description'])); ?>
        </div>
    </div>

    <!-- Notes/Comments Section -->
    <div style="margin-top: 30px;">
        <h3>Activity Log</h3>

        <!-- Form to add comment -->
        <form action="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" method="POST" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="comment_text">Add Comment</label>
                <textarea name="comment_text" id="comment_text" rows="3" required placeholder="Type your update here..."></textarea>
            </div>

            <?php if ($_SESSION['role'] !== 'end_user'): ?>
                <div class="form-group">
                     <label>
                        <input type="checkbox" name="is_internal" value="1">
                        Internal Note (Visible only to Support Team)
                    </label>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-sm">Post Comment</button>
        </form>

        <!-- List Comments -->
        <?php if (empty($comments)): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <?php
                    // Visibility Check
                    if ($comment['is_internal'] && $_SESSION['role'] === 'end_user') continue;
                ?>
                <div style="border-bottom: 1px solid #eee; padding: 10px 0; <?php echo $comment['is_internal'] ? 'background-color: #fff3cd; padding: 10px;' : ''; ?>">
                    <strong><?php echo htmlspecialchars($comment['commenter_name']); ?></strong>
                    <span style="font-size: 12px; color: #777;">
                        (<?php echo htmlspecialchars($comment['created_at']); ?>)
                        <?php if($comment['is_internal']) echo " - [INTERNAL NOTE]"; ?>
                    </span>
                    <p style="margin: 5px 0;"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
