<?php include 'app/views/layouts/header.php'; ?>

<h2>Historial de Movimientos</h2>

<div class="mb-3">
    <button onclick="window.print()" class="btn btn-secondary me-2"><i class="bi bi-printer"></i> Imprimir</button>
    <a href="index.php?page=movements&action=export" class="btn btn-success">
        <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
    </a>
</div>

<table class="table table-striped table-hover mt-3">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Código</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movements as $m): ?>
        <tr>
            <td><?php echo htmlspecialchars($m['date']); ?></td>
            <td><?php echo htmlspecialchars($m['product_name']); ?></td>
            <td><?php echo htmlspecialchars($m['code']); ?></td>
            <td>
                <?php if($m['type'] == 'entrada'): ?>
                    <span class="badge bg-success">Entrada</span>
                <?php else: ?>
                    <span class="badge bg-danger">Salida</span>
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($m['quantity']); ?></td>
            <td><?php echo htmlspecialchars($m['username']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'app/views/layouts/footer.php'; ?>
