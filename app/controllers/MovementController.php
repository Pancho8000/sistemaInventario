<?php
include_once 'app/config/database.php';
include_once 'app/models/Movement.php';
include_once 'app/models/Product.php';

class MovementController {
    private $db;
    private $movement;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->movement = new Movement($this->db);
        $this->product = new Product($this->db);
    }

    public function create() {
        // Obtener lista de productos para el select
        $stmt = $this->product->read();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/movements/create.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->movement->product_id = $_POST['product_id'];
            $this->movement->type = $_POST['type'];
            $this->movement->quantity = $_POST['quantity'];
            $this->movement->user_id = $_SESSION['user_id']; // Asumiendo que está logueado

            if ($this->movement->create()) {
                if (isset($_POST['redirect_to']) && $_POST['redirect_to'] == 'scan') {
                    header("Location: index.php?page=movements&action=scan&success=1");
                } else {
                    header("Location: index.php?page=products");
                }
            } else {
                echo "Error al registrar movimiento.";
            }
        }
    }

    public function scan() {
        $product_data = null;
        $message = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['code'])) {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->product->code = trim($_POST['code']);
            if ($this->product->getByCode()) {
                $product_data = $this->product;
            } else {
                $message = "Producto no encontrado con el código: " . $_POST['code'];
            }
        }
        include 'app/views/movements/scan.php';
    }

    public function history() {
        $stmt = $this->movement->history();
        $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/movements/history.php';
    }

    public function export() {
        $stmt = $this->movement->history();
        $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "movimientos_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // BOM para Excel
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputcsv($output, array('Fecha', 'Producto', 'Código', 'Tipo', 'Cantidad', 'Usuario'));

        foreach ($movements as $m) {
            fputcsv($output, array(
                $m['date'],
                $m['product_name'],
                $m['code'],
                ucfirst($m['type']),
                $m['quantity'],
                $m['username']
            ));
        }

        fclose($output);
        exit();
    }
}
?>
