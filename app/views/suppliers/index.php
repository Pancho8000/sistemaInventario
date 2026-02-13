<?php include 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Listado de Proveedores</h2>
    <a href="index.php?page=suppliers&action=create" class="btn btn-primary">Nuevo Proveedor</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                    <td><?php echo htmlspecialchars($supplier['address']); ?></td>
                    <td>
                        <a href="index.php?page=suppliers&action=edit&id=<?php echo $supplier['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="index.php?page=suppliers&action=delete&id=<?php echo $supplier['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
                            <?php echo Csrf::getTokenInput(); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/layouts/footer.php'; ?>
