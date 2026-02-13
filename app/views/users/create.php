<?php include 'app/views/layouts/header.php'; ?>

<h2>Nuevo Usuario</h2>

<form action="index.php?page=users&action=store" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <div class="mb-3">
        <label for="username" class="form-label">Nombre de Usuario</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Rol</label>
        <select class="form-control" id="role" name="role">
            <option value="user">Usuario (Empleado)</option>
            <option value="admin">Administrador</option>
        </select>
        <div class="form-text">Los administradores tienen acceso total. Los usuarios tienen acceso restringido (futura implementación).</div>
    </div>
    
    <button type="submit" class="btn btn-success">Crear Usuario</button>
    <a href="index.php?page=users" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
