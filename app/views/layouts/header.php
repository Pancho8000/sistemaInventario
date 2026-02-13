<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-shop"></i> Mi Tiendita</h4>
        </div>
        
        <a href="index.php?page=users&action=profile">
            <i class="bi bi-person-circle"></i> Mi Perfil
        </a>
        <a href="index.php?page=logout" class="text-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
        
        <hr style="border-color: rgba(255,255,255,0.1); margin: 10px 0;">
        
        <a href="index.php?page=dashboard" class="<?php echo (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="index.php?page=sales&action=create" class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'sales') ? 'active' : ''; ?>">
            <i class="bi bi-cart-check"></i> Punto de Venta
        </a>
        
        <a href="index.php?page=products" class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'products' && !isset($_GET['action'])) ? 'active' : ''; ?>">
            <i class="bi bi-box-seam"></i> Productos
        </a>
        
        <div class="text-white-50 small px-3 mt-3 mb-1 text-uppercase">Inventario</div>
        
        <a href="index.php?page=movements&action=create">
            <i class="bi bi-arrow-left-right"></i> Movimientos
        </a>
        <a href="index.php?page=movements&action=scan">
            <i class="bi bi-qr-code-scan"></i> Escáner QR
        </a>
        <a href="index.php?page=movements&action=history">
            <i class="bi bi-clock-history"></i> Historial
        </a>
        <a href="index.php?page=products&action=low_stock">
            <i class="bi bi-exclamation-triangle"></i> Bajo Stock
        </a>
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="text-white-50 small px-3 mt-3 mb-1 text-uppercase">Administración</div>
            
            <a href="index.php?page=categories">
                <i class="bi bi-tags"></i> Categorías
            </a>
            <a href="index.php?page=suppliers">
                <i class="bi bi-truck"></i> Proveedores
            </a>
            <a href="index.php?page=users">
                <i class="bi bi-people"></i> Usuarios
            </a>
            <a href="index.php?page=sales&action=daily">
                <i class="bi bi-file-earmark-bar-graph"></i> Reportes / Corte
            </a>
            <a href="index.php?page=settings">
                <i class="bi bi-gear"></i> Configuración
            </a>
        <?php endif; ?>
        
    </div>
    <div class="content">