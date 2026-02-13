<?php include 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Listado de Categorías</h2>
    <a href="index.php?page=categories&action=create" class="btn btn-primary">Nueva Categoría</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                    <td>
                        <a href="index.php?page=categories&action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="index.php?page=categories&action=delete&id=<?php echo $category['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                            <?php echo Csrf::getTokenInput(); ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/layouts/footer.php'; ?>
