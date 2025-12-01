<div class="page-header">
    <h1 class="page-title">Reportes y Analítica</h1>
</div>

<div class="filter-section">
    <div class="filter-group">
        <label class="form-label">Tipo de Reporte</label>
        <select class="form-control">
            <option>Incidentes</option>
            <option>Problemas</option>
            <option>Cambios</option>
            <option>Disponibilidad</option>
            <option>Satisfacción</option>
        </select>
    </div>
    <div class="filter-group">
        <label class="form-label">Fecha Inicio</label>
        <input type="date" class="form-control" value="<?php echo date('Y-m-01'); ?>">
    </div>
    <div class="filter-group">
        <label class="form-label">Fecha Fin</label>
        <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div class="filter-group">
        <label class="form-label">Equipo</label>
        <select class="form-control">
            <option>Todos</option>
            <option>Infrastructure Team</option>
            <option>Application Team</option>
            <option>Database Team</option>
        </select>
    </div>
    <div class="filter-buttons" style="display: flex; gap: 10px; align-items: flex-end;">
        <button class="btn-primary"><i class="fas fa-search"></i> Generar</button>
        <button class="btn-secondary"><i class="fas fa-download"></i> Exportar</button>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-title">Total Incidentes</div>
        <div class="kpi-value">847</div>
        <div class="kpi-trend positive"><i class="fas fa-arrow-up"></i> +12% vs. mes anterior</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">Tiempo Promedio Resolución</div>
        <div class="kpi-value">4.2h</div>
        <div class="kpi-trend positive"><i class="fas fa-arrow-down"></i> -0.5h vs. mes anterior</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">Cumplimiento SLA</div>
        <div class="kpi-value">94.2%</div>
        <div class="kpi-trend positive"><i class="fas fa-arrow-up"></i> +2.1% vs. mes anterior</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-title">Satisfacción Cliente</div>
        <div class="kpi-value">4.6/5</div>
        <div class="kpi-trend positive"><i class="fas fa-arrow-up"></i> +0.2 vs. mes anterior</div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-title">Incidentes por Día</div>
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
