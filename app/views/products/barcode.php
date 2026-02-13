<?php include 'app/views/layouts/header.php'; ?>

<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-white">
                    <h4>Código de Barras</h4>
                </div>
                <div class="card-body">
                    <h3 class="card-title mb-3"><?php echo $this->product->name; ?></h3>
                    <p class="text-muted"><?php echo $this->product->description; ?></p>
                    
                    <div class="barcode-container mb-4">
                        <svg id="barcode"></svg>
                    </div>

                    <div class="d-grid gap-2">
                        <button onclick="printBarcode()" class="btn btn-primary btn-lg"><i class="bi bi-printer"></i> Imprimir</button>
                        <a href="index.php?page=products" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JsBarcode Library -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="public/js/barcode.js"></script>
<script>
    // Generar código de barras
    JsBarcode("#barcode", "<?php echo $this->product->code; ?>", {
        format: "CODE128",
        lineColor: "#000",
        width: 2,
        height: 100,
        displayValue: true
    });
</script>

<?php include 'app/views/layouts/footer.php'; ?>
