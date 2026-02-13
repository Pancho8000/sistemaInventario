<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta #<?php echo $this->sale->id; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 300px; /* Ancho típico para pruebas, la impresora cortará */
        }
        .header, .footer {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        th, td {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        @media print {
            body { margin: 0; padding: 0; }
            #print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h3 style="margin:0"><?php echo isset($settings['company_name']) ? htmlspecialchars($settings['company_name']) : 'Mi Tienda'; ?></h3>
        <p style="margin:0"><?php echo isset($settings['company_address']) ? htmlspecialchars($settings['company_address']) : 'Dirección Local'; ?></p>
        <p style="margin:0">Tel: <?php echo isset($settings['company_phone']) ? htmlspecialchars($settings['company_phone']) : '555-0000'; ?></p>
        <div class="divider"></div>
        <p style="margin:0">Ticket: #<?php echo str_pad($this->sale->id, 6, '0', STR_PAD_LEFT); ?></p>
        <p style="margin:0">Fecha: <?php echo $this->sale->created_at; ?></p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Cant</th>
                <th>Prod</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $item): ?>
            <tr>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo substr($item['product_name'], 0, 15); ?></td>
                <td class="right"><?php echo number_format($item['subtotal'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr class="bold">
            <td>TOTAL:</td>
            <td class="right">$<?php echo number_format($this->sale->total, 2); ?></td>
        </tr>
        <tr>
            <td>Efectivo:</td>
            <td class="right">$<?php echo number_format($this->sale->total, 2); ?></td>
        </tr>
        <tr>
            <td>Cambio:</td>
            <td class="right">$0.00</td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Vuelva pronto</p>
    </div>

    <button id="print-btn" onclick="window.print()" style="display:block; width:100%; margin-top:20px; padding:10px;">IMPRIMIR</button>
    <script>
        // Auto-imprimir al cargar
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>