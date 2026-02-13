<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; color: #333; }
        h1, h2, h3 { color: #003366; }
        .cover { text-align: center; margin-top: 150px; }
        .cover h1 { font-size: 36px; margin-bottom: 10px; }
        .cover p { font-size: 18px; color: #666; }
        .page-break { page-break-before: always; }
        .content { margin-top: 30px; }
        .step { margin-bottom: 20px; border-left: 4px solid #003366; padding-left: 15px; }
        .step-title { font-weight: bold; font-size: 16px; display: block; margin-bottom: 5px; }
        .note { background: #fff3cd; padding: 10px; border-radius: 5px; font-size: 12px; margin-top: 10px; }
    </style>
</head>
<body>

    <!-- PORTADA -->
    <div class="cover">
        <h1>Manual de Usuario</h1>
        <h2>Sistema de Inventario y Ventas (POS)</h2>
        <p>Versión 1.0</p>
        <br><br><br>
        <p><strong>Francisco Canales Ulloa</strong><br>Ingeniero Informático</p>
    </div>

    <div class="page-break"></div>

    <!-- INDICE / INTRODUCCION -->
    <div class="content">
        <h2>1. Acceso al Sistema</h2>
        <p>Para ingresar al sistema, haga doble clic en el archivo <strong>INICIAR_SISTEMA.bat</strong> que se encuentra en el escritorio. Esto abrirá automáticamente el navegador.</p>
        
        <div class="step">
            <span class="step-title">Paso 1: Iniciar Sesión</span>
            Ingrese su nombre de usuario y contraseña proporcionados por el administrador.
            <br><em>Credenciales por defecto: admin / admin123</em>
        </div>
    </div>

    <div class="content">
        <h2>2. Gestión de Productos</h2>
        <p>Aquí podrá agregar, editar o eliminar productos de su inventario.</p>

        <div class="step">
            <span class="step-title">Crear un Nuevo Producto</span>
            1. Vaya al menú lateral y seleccione <strong>Productos</strong> > <strong>Nuevo Producto</strong>.<br>
            2. Complete los campos requeridos:<br>
            <ul>
                <li><strong>Nombre:</strong> Descripción del producto (ej. Arroz 1kg).</li>
                <li><strong>Código:</strong> Código de barras (puede usar el lector).</li>
                <li><strong>Precio Compra/Venta:</strong> Valores netos.</li>
                <li><strong>Stock:</strong> Cantidad inicial.</li>
                <li><strong>A Granel:</strong> Marque esta casilla si el producto se vende por peso (kilos/gramos).</li>
            </ul>
            3. Haga clic en <strong>Guardar</strong>.
        </div>
    </div>

    <div class="page-break"></div>

    <div class="content">
        <h2>3. Realizar una Venta (Punto de Venta)</h2>
        <p>El módulo principal para la atención al público.</p>

        <div class="step">
            <span class="step-title">Proceso de Venta</span>
            1. En el menú, seleccione <strong>Nueva Venta</strong>.<br>
            2. <strong>Escanee el producto</strong> con el lector o búsquelo por nombre escribiendo en el campo de búsqueda.<br>
            3. Si es un producto a granel (ej. Pan), el sistema le pedirá ingresar la cantidad exacta (ej. 0.5 para medio kilo).<br>
            4. Verifique el total y haga clic en <strong>Procesar Venta</strong>.<br>
            5. Ingrese el monto con el que paga el cliente para calcular el vuelto.
        </div>
        
        <div class="note">
            <strong>Nota:</strong> Al finalizar la venta, se generará automáticamente el ticket interno y se descontará el stock del inventario.
        </div>
    </div>

    <div class="content">
        <h2>4. Cierre de Caja y Reportes</h2>
        
        <div class="step">
            <span class="step-title">Ver Ganancias del Día</span>
            Vaya a <strong>Reportes</strong> > <strong>Ventas del Día</strong>. Aquí verá un resumen de todas las transacciones, el total vendido y la ganancia calculada (Venta - Costo).
        </div>
    </div>
    
    <div class="content" style="margin-top: 50px; border-top: 1px solid #ccc; padding-top: 20px;">
        <h3>Soporte Técnico</h3>
        <p>Si tiene dudas o problemas técnicos, contacte a soporte:</p>
        <p><strong>Francisco Canales Ulloa</strong><br>+56 9 7619 1328<br>f.ulloanecro@gmail.com</p>
    </div>

</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output();
file_put_contents('Manual_Usuario.pdf', $output);
echo "Manual de usuario generado exitosamente.";
?>
