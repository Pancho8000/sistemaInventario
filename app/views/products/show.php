<?php include 'app/views/layouts/header.php'; ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Detalles del Producto</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?page=products" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?page=products&action=edit&id=<?php echo $this->product->id; ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title"><?php echo htmlspecialchars($this->product->name); ?></h5>
                    <p class="card-text"><strong>Código:</strong> <?php echo htmlspecialchars($this->product->code); ?></p>
                    <p class="card-text"><strong>Categoría:</strong> <?php echo htmlspecialchars($this->product->category_name); ?></p>
                    <p class="card-text"><strong>Descripción:</strong> <?php echo htmlspecialchars($this->product->description); ?></p>
                </div>
                <div class="col-md-6">
                    <h3 class="text-primary">Stock: <?php echo htmlspecialchars($this->product->stock); ?></h3>
                    <h4>Precio: $<?php echo number_format($this->product->price, 2); ?></h4>
                    <div class="mt-3">
                        <a href="index.php?page=products&action=barcode&id=<?php echo $this->product->id; ?>" class="btn btn-info text-white" target="_blank">
                            <i class="bi bi-upc-scan"></i> Ver Código de Barras
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3>Historial de Movimientos</h3>
    <?php if (count($movements) > 0): ?>
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movements as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['date']); ?></td>
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
    <?php else: ?>
        <div class="alert alert-info">No hay movimientos registrados para este producto.</div>
    <?php endif; ?>
</div>

<?php include 'app/views/layouts/footer.php'; ?>