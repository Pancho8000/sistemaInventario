<?php include 'app/views/layouts/header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-5">
        <h2 class="fw-bold text-primary">Reporte de Ventas</h2>
        <p class="text-muted mb-0">
            <i class="bi bi-calendar-range"></i> 
            <?php echo date('d/m/Y', strtotime($start_date)); ?> - <?php echo date('d/m/Y', strtotime($end_date)); ?>
        </p>
    </div>
    <div class="col-md-7">
        <form action="index.php" method="GET" class="row g-2 justify-content-md-end">
            <input type="hidden" name="page" value="sales">
            <input type="hidden" name="action" value="daily">
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
            </div>
            <div class="col-auto d-flex align-items-center text-muted">-</div>
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-calendar-event-fill"></i></span>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary px-3">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-gradient-success border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #0f9d58 0%, #0b7d46 100%);">
            <div class="card-header border-0 rgba-white-light">Total Vendido en Periodo</div>
            <div class="card-body">
                <h2 class="card-title display-6 fw-bold">$<?php echo number_format($daily_stats['total_sales'] ?? 0, 2); ?></h2>
                <p class="card-text opacity-75">En <?php echo $daily_stats['count_sales'] ?? 0; ?> ventas registradas</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-secondary"><i class="bi bi-list-check me-2"></i>Detalle de Transacciones</h5>
                <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Imprimir</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Ticket #</th>
                                <th>Fecha/Hora</th>
                                <th>Cajero</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($sales) > 0): ?>
                                <?php foreach($sales as $sale): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-primary"><?php echo str_pad($sale['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="small text-dark"><?php echo date('d/m/Y', strtotime($sale['created_at'])); ?></div>
                                        <div class="small text-muted"><?php echo date('H:i', strtotime($sale['created_at'])); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($sale['username']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">$<?php echo number_format($sale['total'], 2); ?></td>
                                    <td class="text-center">
                                        <a href="index.php?page=sales&action=ticket&id=<?php echo $sale['id']; ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                            <i class="bi bi-receipt"></i> Ver Ticket
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">No hay ventas registradas en este periodo.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>