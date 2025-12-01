<div class="login-container">
    <div class="login-header">
        <div class="logo"><i class="fas fa-ticket-alt"></i></div>
        <h1 class="login-title">ITIL Ticketing</h1>
        <p class="login-subtitle">Gestión de Incidentes y Servicios</p>
    </div>

    <form action="index.php?page=login" method="POST">
        <div class="form-group">
            <label class="form-label" for="tenant_code">Código de Organización</label>
            <input type="text" name="tenant_code" id="tenant_code" class="form-control" placeholder="e.g. ACME" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="tu@empresa.com" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="form-checkbox" style="display: flex; align-items: center; margin-bottom: 24px;">
            <input type="checkbox" id="remember" style="width: 18px; height: 18px; margin-right: 8px;">
            <label for="remember" style="font-size: 14px; color: #666; cursor: pointer; flex: 1;">Recuérdame</label>
            <a href="#" class="forgot-password" style="font-size: 14px; color: #2180AE; text-decoration: none;">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>Iniciar Sesión
        </button>
    </form>

    <div class="divider" style="display: flex; align-items: center; margin: 24px 0; color: #ccc;">
        <span style="flex:1; height:1px; background:#e0e0e0;"></span>
        <span style="padding: 0 12px; font-size: 14px; color: #999;">¿No tienes cuenta?</span>
        <span style="flex:1; height:1px; background:#e0e0e0;"></span>
    </div>

    <div class="signup-link" style="text-align: center; font-size: 14px; color: #666;">
        <p>Regístrate en 30 segundos: <a href="index.php?page=register_tenant" style="color: #2180AE; text-decoration: none; font-weight: 600;">Crear Cuenta</a></p>
    </div>
</div>
