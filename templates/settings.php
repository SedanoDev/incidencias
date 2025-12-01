<div class="page-header">
    <h1 class="page-title">Configuración</h1>
</div>

<div class="settings-wrapper">
    <div class="settings-sidebar">
        <ul class="settings-menu">
            <li class="settings-item">
                <a href="#" class="settings-link active" onclick="showSection('general')">
                    <i class="fas fa-cogs"></i>
                    <span>General</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('users')">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('teams')">
                    <i class="fas fa-object-group"></i>
                    <span>Equipos</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('categories')">
                    <i class="fas fa-list"></i>
                    <span>Categorías</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('sla')">
                    <i class="fas fa-hourglass-end"></i>
                    <span>SLA</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('notifications')">
                    <i class="fas fa-bell"></i>
                    <span>Notificaciones</span>
                </a>
            </li>
            <li class="settings-item">
                <a href="#" class="settings-link" onclick="showSection('security')">
                    <i class="fas fa-shield-alt"></i>
                    <span>Seguridad</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="settings-content">
        <!-- General Section -->
        <div id="general" class="content-section active">
            <h2 class="section-title">Configuración General</h2>

            <div class="form-group">
                <label class="form-label">Nombre de la Organización</label>
                <input type="text" class="form-control" value="Acme Corporation">
            </div>

            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea class="form-control">Centro de servicios de TI para Acme Corporation</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email de Contacto</label>
                    <input type="email" class="form-control" value="support@acme.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" value="+34 900 123 456">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Zona Horaria</label>
                <select class="form-control">
                    <option selected>Europe/Madrid (UTC+1)</option>
                    <option>Europe/London (UTC+0)</option>
                    <option>America/New_York (UTC-5)</option>
                    <option>Asia/Tokyo (UTC+9)</option>
                </select>
            </div>

            <div class="button-group" style="display: flex; gap: 10px; margin-top: 20px;">
                <button class="btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                <button class="btn-secondary">Cancelar</button>
            </div>
        </div>

        <!-- Users Section Placeholder -->
        <div id="users" class="content-section">
            <h2 class="section-title">Gestión de Usuarios</h2>
            <p>User management interface here (see User List page).</p>
        </div>

        <!-- Other sections placeholders -->
        <div id="teams" class="content-section">
            <h2 class="section-title">Gestión de Equipos</h2>
            <p>Teams configuration.</p>
        </div>

        <!-- ... (Other sections would go here) -->
    </div>
</div>

<script>
    function showSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(section => {
            section.classList.remove('active');
        });
        document.querySelectorAll('.settings-link').forEach(link => {
            link.classList.remove('active');
        });
        document.getElementById(sectionId).classList.add('active');
        event.target.closest('.settings-link').classList.add('active');
        event.preventDefault();
    }
</script>
