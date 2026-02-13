<?php include 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-secondary">Dashboard</h2>
        <p class="text-muted">Resumen general de tu negocio</p>
    </div>
    <div class="text-end">
        <span class="badge bg-light text-dark border p-2"><?php echo date('d/m/Y'); ?></span>
    </div>
</div>

<div class="row g-4">
    <!-- Card Productos -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f9d58 0%, #0b7d46 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Productos</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $total_products; ?></h2>
                    </div>
                    <i class="bi bi-box-seam" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <small class="mt-3 d-block" style="opacity: 0.8;">Inventario total</small>
            </div>
        </div>
    </div>

    <!-- Card Movimientos -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Movimientos Hoy</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $movements_today; ?></h2>
                    </div>
                    <i class="bi bi-arrow-left-right" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <small class="mt-3 d-block" style="opacity: 0.8;">Operaciones del día</small>
            </div>
        </div>
    </div>

    <!-- Card Bajo Stock -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Bajo Stock</h6>
                        <h2 class="display-6 fw-bold mb-0"><?php echo $low_stock; ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.3;"></i>
                </div>
                <a href="index.php?page=products&action=low_stock" class="text-white text-decoration-none mt-3 d-block small">
                    Ver detalles <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Card Usuarios (Opcional) -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm bg-white text-secondary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 text-muted">Acceso Rápido</h6>
                        <a href="index.php?page=sales&action=create" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-cart-plus"></i> Nueva Venta
                        </a>
                    </div>
                    <i class="bi bi-lightning-charge text-warning" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-bar-chart-line me-2"></i>Actividad Reciente</h5>
                <small class="text-muted">Últimos 7 días</small>
            </div>
            <div class="card-body">
                <canvas id="weeklyChart" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('weeklyChart');
    
    // Configuración global de fuentes para Chart.js
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#6c757d';

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [{
                label: 'Entradas',
                data: <?php echo json_encode($chart_inputs); ?>,
                backgroundColor: '#0f9d58',
                borderRadius: 4,
                barPercentage: 0.6,
            },
            {
                label: 'Salidas',
                data: <?php echo json_encode($chart_outputs); ?>,
                backgroundColor: '#e74c3c',
                borderRadius: 4,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(44, 62, 80, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: '#f0f0f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

<?php include 'app/views/layouts/footer.php'; ?>
