<?php include 'app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Configuración del Sistema</h4>
                <a href="index.php?page=settings&action=backup" class="btn btn-warning btn-sm">
                    <i class="bi bi-database-down"></i> Descargar Backup BD
                </a>
            </div>
            <div class="card-body">
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?page=settings&action=update" method="POST">
                    <?php echo Csrf::getTokenInput(); ?>
                    
                    <h5 class="mb-3 border-bottom pb-2">Información de la Empresa</h5>
                    
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" 
                               value="<?php echo isset($settings['company_name']) ? htmlspecialchars($settings['company_name']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="company_address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="company_address" name="company_address" rows="2"><?php echo isset($settings['company_address']) ? htmlspecialchars($settings['company_address']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="company_phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="company_phone" name="company_phone" 
                               value="<?php echo isset($settings['company_phone']) ? htmlspecialchars($settings['company_phone']) : ''; ?>">
                    </div>

                    <h5 class="mb-3 border-bottom pb-2 mt-4">Configuración General</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="currency_symbol" class="form-label">Símbolo de Moneda</label>
                                <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" 
                                       value="<?php echo isset($settings['currency_symbol']) ? htmlspecialchars($settings['currency_symbol']) : '$'; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_rate" class="form-label">Impuesto / IVA (%)</label>
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" 
                                       value="<?php echo isset($settings['tax_rate']) ? htmlspecialchars($settings['tax_rate']) : '16'; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Guardar Configuración</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>