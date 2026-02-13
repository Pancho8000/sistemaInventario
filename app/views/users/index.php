<?php include 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestión de Usuarios</h2>
    <a href="index.php?page=users&action=create" class="btn btn-primary">Nuevo Usuario</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td>
                <?php if ($user['role'] == 'admin'): ?>
                    <span class="badge bg-danger">Administrador</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Usuario</span>
                <?php endif; ?>
            </td>
            <td><?php echo $user['created_at']; ?></td>
            <td>
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form action="index.php?page=users&action=delete&id=<?php echo $user['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                        <?php echo Csrf::getTokenInput(); ?>
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                <?php else: ?>
                    <span class="text-muted small">Actual</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/layouts/footer.php'; ?>
