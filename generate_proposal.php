<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar opciones de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// Contenido HTML de la propuesta
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #003366; padding-bottom: 20px; }
        .header h1 { color: #003366; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { color: #555; margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        
        .section { margin-bottom: 25px; }
        .section-title { 
            color: #003366; 
            border-bottom: 1px solid #ccc; 
            padding-bottom: 5px; 
            margin-bottom: 10px; 
            font-size: 14px; 
            font-weight: bold; 
            text-transform: uppercase;
        }
        
        .content-text { text-align: justify; margin-bottom: 10px; }
        
        .features-grid { display: table; width: 100%; margin-top: 10px; }
        .feature-item { display: table-cell; width: 48%; padding-right: 2%; vertical-align: top; }
        
        .pricing-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
        .pricing-table th, .pricing-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .pricing-table th { background-color: #003366; color: white; text-align: center; }
        .pricing-table tr:nth-child(even) { background-color: #f2f2f2; }
        .pricing-table td.price { text-align: right; font-weight: bold; color: #003366; }
        
        .contact-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 30px;
            text-align: center;
            border-radius: 5px;
        }
        .contact-name { font-weight: bold; font-size: 14px; color: #003366; }
        
        ul { list-style-type: none; padding: 0; margin: 0; }
        ul li { margin-bottom: 5px; padding-left: 15px; position: relative; }
        ul li:before { content: "•"; color: #003366; font-weight: bold; position: absolute; left: 0; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Propuesta de Solución Tecnológica</h1>
        <h2>Sistema Integral de Gestión de Ventas e Inventario (POS)</h2>
    </div>

    <div class="section">
        <div class="section-title">01. Resumen Ejecutivo</div>
        <div class="content-text">
            <p>La presente propuesta tiene como objetivo la implementación de una solución tecnológica robusta y eficiente, diseñada específicamente para optimizar los procesos operativos de su negocio. Nuestro sistema permite digitalizar el control de ventas e inventarios, eliminando pérdidas por desorganización y agilizando la atención al cliente.</p>
            <p>Esta herramienta está desarrollada pensando en la realidad del comercio local: es intuitiva, rápida y garantiza la continuidad operativa al no depender de una conexión a internet permanente.</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">02. Alcance de la Solución</div>
        <div class="features-grid">
            <div class="feature-item">
                <strong>Gestión Comercial Eficiente</strong>
                <ul>
                    <li>Punto de Venta (POS) optimizado para atención rápida.</li>
                    <li>Compatibilidad con lectores de código de barras.</li>
                    <li>Soporte para venta de productos unitarios y a granel (pesables).</li>
                    <li>Emisión de tickets de venta internos.</li>
                </ul>
            </div>
            <div class="feature-item">
                <strong>Control y Seguridad</strong>
                <ul>
                    <li>Control de inventario en tiempo real con alertas de stock crítico.</li>
                    <li>Perfiles de usuario con permisos diferenciados (Administrador/Vendedor).</li>
                    <li>Reportes detallados de ventas diarias y mensuales.</li>
                    <li>Funcionamiento local 100% autónomo (Offline).</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">03. Inversión y Servicios</div>
        <p>A continuación, se detalla la estructura de costos transparente y sin cláusulas ocultas.</p>
        <table class="pricing-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Servicio</th>
                    <th style="width: 40%;">Descripción</th>
                    <th style="width: 20%;">Inversión (CLP)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Licencia de Software Perpetua</strong><br><i>(Pago Único)</i></td>
                    <td>Incluye instalación en 1 equipo, configuración personalizada (logo, datos), carga inicial de categorías y capacitación presencial/remota.</td>
                    <td class="price">$150.000</td>
                </tr>
                <tr>
                    <td><strong>Plan de Continuidad Operativa</strong><br><i>(Mensual - Opcional)</i></td>
                    <td>Soporte técnico prioritario, respaldos de seguridad mensuales y actualizaciones de mejora del sistema.</td>
                    <td class="price">$25.000 / mes</td>
                </tr>
                <tr>
                    <td><strong>Soporte Técnico por Evento</strong><br><i>(A requerimiento)</i></td>
                    <td>Visita técnica para resolución de fallas de hardware, virus o reconfiguración fuera del plan mensual.</td>
                    <td class="price">$35.000 / visita</td>
                </tr>
            </tbody>
        </table>
        <p style="font-size: 10px; color: #666; margin-top: 5px;">* Precios referenciales en Pesos Chilenos (CLP). Equivalencia aproximada en USD sujeta al tipo de cambio del día.</p>
    </div>

    <div class="section">
        <div class="section-title">04. Requisitos Técnicos</div>
        <div class="content-text">
            <p>Para garantizar el óptimo funcionamiento del sistema, el cliente deberá disponer de:</p>
            <ul>
                <li>Computador (PC o Notebook) con sistema operativo Windows 10 o superior.</li>
                <li>4 GB de memoria RAM mínimo.</li>
                <li>(Opcional) Lector de código de barras USB.</li>
                <li>(Opcional) Impresora térmica de 58mm o 80mm para tickets.</li>
            </ul>
        </div>
    </div>

    <div class="contact-box">
        <p>Quedo a su disposición para coordinar una demostración sin compromiso y resolver cualquier duda.</p>
        <p class="contact-name">Francisco Canales Ulloa</p>
        <p>Ingeniero Informático</p>
        <p>Teléfono / WhatsApp: <strong>+56 9 7619 1328</strong></p>
        <p>Email: <strong>f.ulloanecro@gmail.com</strong></p>
    </div>

</body>
</html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output = $dompdf->output();
file_put_contents('Propuesta_Comercial_POS.pdf', $output);
echo "PDF actualizado generado exitosamente.";
?>
