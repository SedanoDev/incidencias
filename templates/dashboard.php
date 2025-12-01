<div class="page-header">
    <h1 class="page-title">Bienvenido, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h1>
    <p class="page-subtitle">Resumen de tu centro de soporte técnico</p>
</div>

<!-- KPI Grid (Mock Data for Visuals, ideally populated from DB) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Incidentes Activos</span>
            <div class="kpi-icon blue">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
        <div class="kpi-value"><?php echo count($incidents); ?></div>
        <div class="kpi-trend positive">
            <i class="fas fa-arrow-up"></i>
            <span>+5 vs ayer</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Cumplimiento SLA</span>
            <div class="kpi-icon orange">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="kpi-value">94.2%</div>
        <div class="kpi-trend positive">
            <i class="fas fa-check-circle"></i>
            <span>+2.1% vs mes</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Solicitudes Abiertas</span>
            <div class="kpi-icon green">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="kpi-value">23</div>
        <div class="kpi-trend negative">
            <i class="fas fa-arrow-down"></i>
            <span>-2 vs ayer</span>
        </div>
    </div>

     <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-title">Satisfacción</span>
            <div class="kpi-icon purple">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="kpi-value">4.6</div>
        <div class="kpi-trend positive">
            <i class="fas fa-arrow-up"></i>
            <span>+0.2 vs mes</span>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-title">Incidentes últimos 30 días</div>
        <div class="chart-placeholder">
            <i class="fas fa-chart-line" style="font-size: 48px; color: #ccc; margin-right: 12px;"></i>
            Gráfico de línea
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-title">Distribución por Prioridad</div>
        <div class="chart-placeholder">
            <i class="fas fa-chart-pie" style="font-size: 48px; color: #ccc; margin-right: 12px;"></i>
            Gráfico de pastel
        </div>
    </div>
</div>

<!-- Recent Incidents Table -->
<div class="table-card">
    <div class="table-header">
        <h2 class="table-title"><?php echo ($_SESSION['role'] === 'end_user') ? 'Mis Incidentes Recientes' : 'Incidentes Recientes'; ?></h2>
        <a href="index.php?page=create_incident" class="btn-primary" style="text-decoration: none;">
            <i class="fas fa-plus"></i> Nuevo Incidente
        </a>
    </div>

    <div class="table-container">
        <?php if (empty($incidents)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No active incidents found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Creado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $incident): ?>
                        <tr onclick="window.location='index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>'" style="cursor: pointer;">
                            <td><strong><?php echo htmlspecialchars($incident['incident_number']); ?></strong></td>
                            <td><?php echo htmlspecialchars($incident['title']); ?></td>
                            <td>
                                <span class="priority-badge priority-<?php echo strtolower($incident['priority_name'] ?? 'low'); ?>">
                                    <?php echo htmlspecialchars($incident['priority_name'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge">
                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $incident['status']))); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($incident['assigned_name'] ?? 'Sin asignar'); ?></td>
                            <td><?php echo htmlspecialchars(date('M j, H:i', strtotime($incident['reported_date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
