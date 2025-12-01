<div class="breadcrumb">
    <a href="index.php?page=dashboard">Dashboard</a>
    <i class="fas fa-chevron-right"></i>
    <a href="index.php?page=dashboard">Incidents</a>
    <i class="fas fa-chevron-right"></i>
    <span><?php echo htmlspecialchars($incident['incident_number']); ?></span>
</div>

<div class="detail-layout">
    <div class="detail-main">
        <div class="detail-header">
            <div class="detail-title-section">
                <h2><?php echo htmlspecialchars($incident['title']); ?></h2>
                <div class="detail-meta">
                    <span style="color: var(--color-text-secondary); font-size: var(--font-size-sm);"><?php echo htmlspecialchars($incident['incident_number']); ?></span>
                    <span class="badge badge-<?php echo strtolower($incident['priority_name'] ?? 'low'); ?>"><?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?></span>
                </div>
            </div>

            <?php if ($_SESSION['role'] !== 'end_user'): ?>
            <a href="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <?php endif; ?>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="document.getElementById('tab-details').style.display='block'; document.getElementById('tab-activity').style.display='none'; this.classList.add('active'); this.nextElementSibling.classList.remove('active');">Details</button>
            <button class="tab" onclick="document.getElementById('tab-details').style.display='none'; document.getElementById('tab-activity').style.display='block'; this.classList.add('active'); this.previousElementSibling.classList.remove('active');">Activity</button>
        </div>

        <div id="tab-details" style="display: block;">
            <div class="form-row">
                <div class="form-field" style="grid-column: 1 / -1;">
                    <label>Description</label>
                    <div style="padding: var(--space-12); background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-base);">
                        <?php echo nl2br(htmlspecialchars($incident['description'])); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label>Impact</label>
                    <input type="text" value="<?php echo htmlspecialchars(ucfirst($incident['impact'])); ?>" readonly>
                </div>
                <div class="form-field">
                    <label>Urgency</label>
                    <input type="text" value="<?php echo htmlspecialchars(ucfirst($incident['urgency'])); ?>" readonly>
                </div>
            </div>
        </div>

        <div id="tab-activity" style="display: none;">
            <div class="timeline">
                <?php if (empty($comments)): ?>
                    <p style="padding: 20px; color: var(--color-text-secondary);">No activity recorded.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <?php if ($comment['is_internal'] && $_SESSION['role'] === 'end_user') continue; ?>
                        <div class="timeline-item">
                            <div class="timeline-content" style="<?php echo $comment['is_internal'] ? 'background-color: #fff3cd;' : ''; ?>">
                                <div class="timeline-header">
                                    <span class="timeline-user">
                                        <?php echo htmlspecialchars($comment['commenter_name']); ?>
                                        <?php if($comment['is_internal']) echo " <span style='font-size:10px; color:#856404;'>[INTERNAL]</span>"; ?>
                                    </span>
                                    <span class="timeline-time"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                </div>
                                <div class="timeline-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="margin-top: var(--space-24); border-top: 1px solid var(--color-card-border); padding-top: var(--space-20);">
                <form action="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" method="POST">
                    <div class="form-group">
                        <label class="form-label">Add Comment</label>
                        <textarea name="comment_text" class="form-input" rows="3" required placeholder="Type your comment here..."></textarea>
                    </div>
                    <?php if ($_SESSION['role'] !== 'end_user'): ?>
                        <div class="form-check">
                            <input type="checkbox" name="is_internal" id="is_internal" value="1">
                            <label for="is_internal">Internal Note (Visible only to Support Team)</label>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-comment"></i> Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="detail-sidebar">
        <div class="sidebar-card">
            <h3>Incident Info</h3>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="badge badge-<?php echo strtolower($incident['status']); ?>"><?php echo htmlspecialchars(ucfirst($incident['status'])); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Assigned To</span>
                <span class="info-value"><?php echo htmlspecialchars($incident['assigned_name'] ?? 'Unassigned'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Reported By</span>
                <span class="info-value"><?php echo htmlspecialchars($incident['reporter_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Created</span>
                <span class="info-value"><?php echo htmlspecialchars(date('M j, Y H:i', strtotime($incident['reported_date']))); ?></span>
            </div>
        </div>
    </div>
</div>
