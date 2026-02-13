<?php
include_once 'app/config/database.php';
include_once 'app/models/Sale.php';
include_once 'app/models/Product.php';

class SaleController {
    private $db;
    private $sale;
    private $product;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->sale = new Sale($this->db);
        $this->product = new Product($this->db);
    }

    public function create() {
        // Vista del Punto de Venta (POS)
        include 'app/views/sales/create.php';
    }

    public function store() {
        header('Content-Type: application/json');
        
        // Obtener datos JSON
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data || !isset($data['items']) || empty($data['items'])) {
            echo json_encode(['success' => false, 'message' => 'No hay productos en la venta.']);
            return;
        }

        if (!isset($data['csrf_token']) || !Csrf::verifyToken($data['csrf_token'])) {
             echo json_encode(['success' => false, 'message' => 'Error de validación CSRF']);
             return;
        }

        $this->sale->user_id = $_SESSION['user_id'];
        $this->sale->total = $data['total'];
        $this->sale->payment_method = 'efectivo'; // Por ahora fijo

        if ($this->sale->create($data['items'])) {
            echo json_encode([
                'success' => true, 
                'message' => 'Venta realizada correctamente.',
                'sale_id' => $this->sale->id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al procesar la venta.']);
        }
    }

    public function search_product() {
        header('Content-Type: application/json');
        $term = isset($_GET['term']) ? $_GET['term'] : '';
        
        if (empty($term)) {
            echo json_encode([]);
            return;
        }

        $stmt = $this->product->search($term);
        $products = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'name' => $row['name'],
                'price' => $row['price'],
                'stock' => $row['stock'],
                'is_bulk' => $row['is_bulk']
            ];
        }
        
        echo json_encode($products);
    }

    public function ticket($id) {
        $this->sale->id = $id;
        if ($this->sale->readOne()) {
            $details_stmt = $this->sale->getDetails();
            $details = $details_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener datos de la empresa (si existen)
            include_once 'app/models/Setting.php';
            $setting = new Setting($this->db);
            $settings = $setting->getAll();
            
            include 'app/views/sales/ticket.php';
        } else {
            echo "Venta no encontrada.";
        }
    }

    public function daily_report() {
        // Solo admin
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }

        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        // Totales por rango
        $query_total = "SELECT SUM(total) as total_sales, COUNT(*) as count_sales 
                        FROM sales 
                        WHERE DATE(created_at) BETWEEN :start AND :end";
        $stmt_total = $this->db->prepare($query_total);
        $stmt_total->bindParam(':start', $start_date);
        $stmt_total->bindParam(':end', $end_date);
        $stmt_total->execute();
        $daily_stats = $stmt_total->fetch(PDO::FETCH_ASSOC);
        
        // Obtener todas las ventas del rango para detalle
        $query = "SELECT s.id, s.created_at, s.total, u.username 
                  FROM sales s
                  JOIN users u ON s.user_id = u.id
                  WHERE DATE(s.created_at) BETWEEN :start AND :end
                  ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start', $start_date);
        $stmt->bindParam(':end', $end_date);
        $stmt->execute();
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/sales/daily.php';
    }
}
?>