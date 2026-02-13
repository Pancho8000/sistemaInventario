<?php include 'app/views/layouts/header.php'; ?>

<h2>Registrar Movimiento de Inventario</h2>

<form action="index.php?page=movements&action=store" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <div class="mb-3">
        <label for="product_id" class="form-label">Producto</label>
        <select class="form-control" id="product_id" name="product_id" required>
            <option value="">Seleccione un producto...</option>
            <?php foreach ($products as $p): ?>
                <option value="<?php echo $p['id']; ?>">
                    <?php echo $p['code'] . ' - ' . $p['name'] . ' (Stock actual: ' . $p['stock'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Tipo de Movimiento</label>
        <select class="form-control" id="type" name="type" required>
            <option value="entrada">Entrada (Compra/Devolución)</option>
            <option value="salida">Salida (Venta/Merma)</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Cantidad</label>
        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Registrar</button>
    <a href="index.php?page=dashboard" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
