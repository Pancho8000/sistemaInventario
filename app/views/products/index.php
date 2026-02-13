<?php include 'app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-secondary">Productos</h2>
        <p class="text-muted mb-0">Gestión de catálogo</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
        <a href="index.php?page=products&action=export" class="btn btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Exportar</a>
        <a href="index.php?page=products&action=create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo Producto</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="index.php" method="GET" class="mb-4">
            <input type="hidden" name="page" value="products">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Buscar por nombre, código o descripción..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-primary px-4" type="submit">Buscar</button>
                <?php if(isset($_GET['search'])): ?>
                    <a href="index.php?page=products" class="btn btn-outline-danger px-3"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-3 border-bottom-0">Código</th>
                        <th class="border-bottom-0">Nombre</th>
                        <th class="border-bottom-0">Stock</th>
                        <th class="border-bottom-0">Precio</th>
                        <th class="border-bottom-0">Categoría</th>
                        <th class="text-end pe-3 border-bottom-0">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                No se encontraron productos
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-secondary"><?php echo htmlspecialchars($product['code']); ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></div>
                            </td>
                            <td>
                                <?php 
                                    $stockClass = 'bg-success';
                                    if($product['stock'] <= 5) $stockClass = 'bg-danger';
                                    elseif($product['stock'] <= 10) $stockClass = 'bg-warning text-dark';
                                ?>
                                <span class="badge <?php echo $stockClass; ?> rounded-pill"><?php echo htmlspecialchars($product['stock']); ?></span>
                            </td>
                            <td class="fw-bold text-primary-custom">$<?php echo number_format($product['price'], 2); ?></td>
                            <td><span class="badge bg-light text-secondary border"><?php echo htmlspecialchars($product['category_name']); ?></span></td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?page=products&action=show&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary" title="Ver Detalles"><i class="bi bi-eye"></i></a>
                                    <a href="index.php?page=products&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?page=products&action=barcode&id=<?php echo $product['id']; ?>" class="btn btn-outline-dark" title="Código de Barras"><i class="bi bi-upc-scan"></i></a>
                                    <form action="index.php?page=products&action=delete&id=<?php echo $product['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este producto?');">
                                        <?php echo Csrf::getTokenInput(); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link border-0 rounded-start-pill" href="<?php echo "index.php?page=products&p=" . ($page - 1); ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>">
                        <i class="bi bi-chevron-left"></i> Anterior
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link border-0" href="<?php echo "index.php?page=products&p=" . $i; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link border-0 rounded-end-pill" href="<?php echo "index.php?page=products&p=" . ($page + 1); ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>">
                        Siguiente <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/layouts/footer.php'; ?>
