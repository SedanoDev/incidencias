<div class="breadcrumb">
    <a class="breadcrumb-link" href="index.php?page=dashboard">Dashboard</a>
    <span>/</span>
    <a class="breadcrumb-link" href="index.php?page=incidents">Incidentes</a>
    <span>/</span>
    <span><?php echo htmlspecialchars($incident['incident_number']); ?></span>
</div>

<div class="incident-header">
    <div class="incident-id">Incidente #<?php echo htmlspecialchars($incident['incident_number']); ?></div>
    <div class="incident-title-input">
        <div class="incident-title-text"><?php echo htmlspecialchars($incident['title']); ?></div>
        <?php if ($_SESSION['role'] !== 'end_user'): ?>
        <a href="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" class="edit-btn" style="text-decoration: none;">
            <i class="fas fa-edit"></i>
        </a>
        <?php endif; ?>
    </div>
    <div class="incident-meta">
        <div class="meta-item">
            <div class="meta-label">Estado</div>
            <span class="status-badge" style="width: fit-content;">
                <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $incident['status']))); ?>
            </span>
        </div>
        <div class="meta-item">
            <div class="meta-label">Prioridad</div>
            <div class="priority-badge priority-<?php echo strtolower($incident['priority_name'] ?? 'low'); ?>">
                <?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?>
            </div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Impacto</div>
            <div class="meta-value"><?php echo htmlspecialchars(ucfirst($incident['impact'])); ?></div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Urgencia</div>
            <div class="meta-value"><?php echo htmlspecialchars(ucfirst($incident['urgency'])); ?></div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="main-panel">
        <div class="tabs">
            <div class="tab active" onclick="document.getElementById('tab-details').style.display='block'; document.getElementById('tab-activity').style.display='none'; this.classList.add('active'); this.nextElementSibling.classList.remove('active');">Detalles</div>
            <div class="tab" onclick="document.getElementById('tab-details').style.display='none'; document.getElementById('tab-activity').style.display='block'; this.classList.add('active'); this.previousElementSibling.classList.remove('active');">Actividad</div>
        </div>

        <div id="tab-details" class="tab-content" style="display: block;">
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" readonly style="background: #f9f9f9;"><?php echo htmlspecialchars($incident['description']); ?></textarea>
            </div>

            <!-- Additional fields mock for UI completeness based on design -->
            <div class="form-group">
                <label class="form-label">Categoría</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($incident['category_name'] ?? 'General'); ?>" readonly style="background: #f9f9f9;">
            </div>

            <?php if ($_SESSION['role'] !== 'end_user'): ?>
            <div class="form-group">
                <label class="form-label">Notas de Resolución</label>
                <textarea class="form-control" placeholder="Agregar notas..." readonly style="background: #f9f9f9;"><?php echo htmlspecialchars($incident['resolution_notes'] ?? ''); ?></textarea>
            </div>
            <?php endif; ?>
        </div>

        <div id="tab-activity" class="tab-content" style="display: none;">
            <!-- Comment List -->
            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <p style="color: #999; text-align: center; padding: 20px;">No hay actividad reciente.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <?php if ($comment['is_internal'] && $_SESSION['role'] === 'end_user') continue; ?>
                        <div class="comment" style="<?php echo $comment['is_internal'] ? 'background-color: #fff8e1;' : ''; ?>">
                            <div class="comment-avatar"><?php echo strtoupper(substr($comment['commenter_name'], 0, 2)); ?></div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo htmlspecialchars($comment['commenter_name']); ?></span>
                                    <span class="comment-time"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                    <?php if($comment['is_internal']) echo "<span style='font-size:10px; color:#f39c12; margin-left:5px;'>[INTERNO]</span>"; ?>
                                </div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Comment Form -->
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                <form action="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" method="POST">
                    <div class="form-group">
                        <label class="form-label">Agregar Comentario</label>
                        <textarea name="comment_text" class="form-control" rows="3" required placeholder="Escribe un comentario..."></textarea>
                    </div>
                    <?php if ($_SESSION['role'] !== 'end_user'): ?>
                        <div style="margin-bottom: 16px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                                <input type="checkbox" name="is_internal" value="1">
                                Nota Interna (Visible solo para el equipo)
                            </label>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Publicar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="side-panel">
        <div class="side-section">
            <div class="side-title">Asignado a</div>
            <div class="user-badge">
                <div class="user-badge-avatar"><?php echo strtoupper(substr($incident['assigned_name'] ?? 'U', 0, 2)); ?></div>
                <div><?php echo htmlspecialchars($incident['assigned_name'] ?? 'Sin asignar'); ?></div>
            </div>
        </div>

        <div class="side-section">
            <div class="side-title">Reportado por</div>
            <div class="side-content"><?php echo htmlspecialchars($incident['reporter_name']); ?></div>
        </div>

        <div class="side-section">
            <div class="side-title">SLA</div>
            <!-- Mock logic for SLA timer based on created date -->
            <div class="sla-status warning">
                <i class="fas fa-clock"></i> Pendiente
            </div>
        </div>

        <div class="side-section">
            <div class="side-title">Fechas</div>
            <div class="side-content" style="font-size: 12px; line-height: 1.8;">
                <div><strong>Creado:</strong> <?php echo htmlspecialchars($incident['created_at']); ?></div>
                <div><strong>Actualizado:</strong> <?php echo htmlspecialchars($incident['updated_at']); ?></div>
            </div>
        </div>
    </div>
</div>
