<?php include 'app/views/layouts/header.php'; ?>

<h2>Nuevo Proveedor</h2>

<form action="index.php?page=suppliers&action=store" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Dirección</label>
        <textarea class="form-control" id="address" name="address"></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php?page=suppliers" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
