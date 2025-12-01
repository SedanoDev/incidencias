<div class="breadcrumb">
    <a class="breadcrumb-link" href="index.php?page=dashboard">Dashboard</a>
    <span>/</span>
    <a class="breadcrumb-link" href="index.php?page=incidents">Incidentes</a>
    <span>/</span>
    <span><?php echo htmlspecialchars($incident['incident_number']); ?></span>
    <span>/</span>
    <span>Editar</span>
</div>

<div class="incident-header">
    <div class="incident-id">Editando Incidente #<?php echo htmlspecialchars($incident['incident_number']); ?></div>
    <div class="incident-title-input">
        <div class="incident-title-text"><?php echo htmlspecialchars($incident['title']); ?></div>
    </div>
</div>

<div class="content-wrapper">
    <div class="main-panel">
        <div class="tab-content">
            <form action="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" method="POST">
                <div class="form-group">
                    <label class="form-label">Título</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($incident['title']); ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-control">
                            <?php
                            $statuses = ['new', 'assigned', 'in_progress', 'on_hold', 'resolved', 'closed', 'cancelled'];
                            foreach ($statuses as $st) {
                                $selected = ($incident['status'] === $st) ? 'selected' : '';
                                echo "<option value=\"$st\" $selected>" . ucfirst($st) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asignar a</label>
                        <select name="assigned_to" class="form-control">
                            <option value="">-- Sin asignar --</option>
                            <?php foreach ($tech_users as $tech): ?>
                                <?php $sel = ($incident['assigned_to'] == $tech['user_id']) ? 'selected' : ''; ?>
                                <option value="<?php echo $tech['user_id']; ?>" <?php echo $sel; ?>>
                                    <?php echo htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Impacto</label>
                        <select name="impact" class="form-control">
                            <?php
                            $levels = ['low', 'medium', 'high', 'critical'];
                            foreach ($levels as $lvl) {
                                $selected = ($incident['impact'] === $lvl) ? 'selected' : '';
                                echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urgencia</label>
                        <select name="urgency" class="form-control">
                            <?php
                            foreach ($levels as $lvl) {
                                $selected = ($incident['urgency'] === $lvl) ? 'selected' : '';
                                echo "<option value=\"$lvl\" $selected>" . ucfirst($lvl) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="button-group" style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                    <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="btn-secondary" style="text-decoration: none;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
