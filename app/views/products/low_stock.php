<?php include 'app/views/layouts/header.php'; ?>

<h2>Productos con Stock Bajo (< 10)</h2>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="alert alert-warning mb-0 flex-grow-1 me-3">
        Atención: Se recomienda reabastecer los siguientes productos.
    </div>
    <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> Imprimir</button>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Stock Actual</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['code']; ?></td>
            <td><?php echo $product['name']; ?></td>
            <td><?php echo $product['category_name']; ?></td>
            <td class="text-danger fw-bold"><?php echo $product['stock']; ?></td>
            <td>
                <a href="index.php?page=movements&action=create" class="btn btn-sm btn-primary">Reabastecer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/layouts/footer.php'; ?>
