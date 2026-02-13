<?php include 'app/views/layouts/header.php'; ?>

<h2>Nueva Categoría</h2>

<form action="index.php?page=categories&action=store" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php?page=categories" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
