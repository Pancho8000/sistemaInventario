<?php include 'app/views/layouts/header.php'; ?>

<h2>Editar Categoría</h2>

<form action="index.php?page=categories&action=update" method="POST">
    <?php echo Csrf::getTokenInput(); ?>
    <input type="hidden" name="id" value="<?php echo $this->category->id; ?>">

    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->category->name; ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control" id="description" name="description"><?php echo $this->category->description; ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php?page=categories" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'app/views/layouts/footer.php'; ?>
