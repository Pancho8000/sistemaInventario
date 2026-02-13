<?php include 'app/views/layouts/header.php'; ?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Escáner de Movimientos (Pistola QR)</h2>
            <p class="text-muted">Escanee el código del producto para registrar una entrada o salida.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Movimiento registrado correctamente. Listo para el siguiente.</div>
    <?php endif; ?>

    <?php if (isset($message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Formulario de Búsqueda (Siempre visible si no hay producto seleccionado o para nueva búsqueda) -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="index.php?page=movements&action=scan" method="POST">
                <?php echo Csrf::getTokenInput(); ?>
                <div class="input-group input-group-lg">
                    <span class="input-group-text" id="icon-scan"><i class="bi bi-qr-code"></i></span>
                    <input type="text" class="form-control" name="code" placeholder="Escanee o escriba el código aquí..." autofocus required>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($product_data)): ?>
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                Producto Encontrado
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3><?php echo htmlspecialchars($product_data->name); ?></h3>
                        <p class="lead"><?php echo htmlspecialchars($product_data->description); ?></p>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Código:</strong> <?php echo htmlspecialchars($product_data->code); ?></li>
                            <li class="list-group-item"><strong>Stock Actual:</strong> <span class="badge bg-info text-dark" style="font-size: 1.2em;"><?php echo htmlspecialchars($product_data->stock); ?></span></li>
                            <li class="list-group-item"><strong>Precio:</strong> $<?php echo number_format($product_data->price, 2); ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <form action="index.php?page=movements&action=store" method="POST">
                            <?php echo Csrf::getTokenInput(); ?>
                            <input type="hidden" name="product_id" value="<?php echo $product_data->id; ?>">
                            <input type="hidden" name="redirect_to" value="scan">
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad</label>
                                <input type="number" class="form-control form-control-lg" name="quantity" value="1" min="1" required id="quantity_input">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de Movimiento</label>
                                <div class="d-grid gap-2 d-md-block">
                                    <button type="submit" name="type" value="out" class="btn btn-danger btn-lg">Salida (Venta)</button>
                                    <button type="submit" name="type" value="in" class="btn btn-success btn-lg">Entrada (Compra)</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="public/js/scan.js"></script>
    <?php endif; ?>
</div>

<?php include 'app/views/layouts/footer.php'; ?>
