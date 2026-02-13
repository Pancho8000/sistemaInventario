<?php include 'app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h4>Mi Perfil</h4>
            </div>
            <div class="card-body">
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Rol:</strong> <?php echo ($_SESSION['role'] == 'admin') ? 'Administrador' : 'Usuario'; ?></p>
                
                <hr>
                
                <h5>Cambiar Contraseña</h5>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?page=users&action=update_password" method="POST">
                    <?php echo Csrf::getTokenInput(); ?>
                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>