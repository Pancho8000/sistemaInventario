<?php include 'app/views/layouts/header.php'; ?>

<h2>Nuevo Producto</h2>

<form action="index.php?page=products&action=store" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <div class="mb-3">
        <label for="code" class="form-label">Código</label>
        <input type="text" class="form-control" id="code" name="code">
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock Inicial</label>
        <input type="number" step="0.001" class="form-control" id="stock" name="stock" value="0">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="is_bulk" name="is_bulk" value="1">
        <label class="form-check-label" for="is_bulk">¿Venta a granel? (Permitir decimales)</label>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="0.00">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Categoría</label>
        <select class="form-control" id="category_id" name="category_id">
            <option value="">Seleccione...</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php?page=products" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
