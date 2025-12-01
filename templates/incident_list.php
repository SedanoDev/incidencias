<div class="page-header">
    <h1 class="page-title">Incidentes</h1>
</div>

<div class="toolbar">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Buscar por ID, título o descripción...">
    </div>
    <!-- Visual filters for MVP -->
    <button class="btn-secondary" style="border: 1px solid #e0e0e0; background: white;">
        <i class="fas fa-filter"></i> Filtrar
    </button>
    <button class="btn-secondary" style="border: 1px solid #e0e0e0; background: white;">
        <i class="fas fa-sort"></i> Ordenar
    </button>
    <a href="index.php?page=create_incident" class="btn-primary" style="text-decoration: none;">
        <i class="fas fa-plus"></i> Nuevo Incidente
    </a>
</div>

<div class="table-card">
    <div class="table-container">
        <?php if (empty($incidents)): ?>
            <p style="padding: 20px; color: var(--color-text-secondary);">No incidents found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Asignado a</th>
                        <th>Reportado por</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidents as $incident): ?>
                        <tr onclick="window.location='index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>'" style="cursor: pointer;">
                            <td><strong><?php echo htmlspecialchars($incident['incident_number']); ?></strong></td>
                            <td style="font-weight: 500;"><?php echo htmlspecialchars($incident['title']); ?></td>
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
                            <td><?php echo htmlspecialchars($incident['reporter_name'] ?? 'User'); ?></td>
                            <td><?php echo htmlspecialchars(date('M j, H:i', strtotime($incident['reported_date']))); ?></td>
                            <td onclick="event.stopPropagation()">
                                 <div class="action-icons">
                                     <a href="index.php?page=view_incident&id=<?php echo $incident['incident_id']; ?>" class="action-icon" title="Ver"><i class="fas fa-eye"></i></a>
                                     <?php if ($_SESSION['role'] !== 'end_user'): ?>
                                     <a href="index.php?page=edit_incident&id=<?php echo $incident['incident_id']; ?>" class="action-icon" title="Editar"><i class="fas fa-edit"></i></a>
                                     <?php endif; ?>
                                 </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <!-- Pagination (Visual only for MVP) -->
    <div class="pagination" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-top: 1px solid #e0e0e0; background: #f9f9f9; border-radius: 0 0 12px 12px; font-size: 12px; color: #666;">
        <span>Mostrando 1-<?php echo count($incidents); ?> de <?php echo count($incidents); ?> incidentes</span>
        <div class="pagination-buttons" style="display: flex; gap: 8px;">
            <button class="btn-secondary" style="padding: 6px 10px; min-width: 32px; background: white; border: 1px solid #e0e0e0; border-radius: 6px;"><i class="fas fa-chevron-left"></i></button>
            <button class="btn-primary" style="padding: 6px 10px; min-width: 32px; border-radius: 6px;">1</button>
            <button class="btn-secondary" style="padding: 6px 10px; min-width: 32px; background: white; border: 1px solid #e0e0e0; border-radius: 6px;"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</div>
