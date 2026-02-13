<?php
include_once 'app/config/database.php';
include_once 'app/models/Movement.php';

class DashboardController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Total productos
        $query = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Productos stock bajo (< 10)
        $query = "SELECT COUNT(*) as total FROM products WHERE stock < 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $low_stock = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total movimientos hoy
        $query = "SELECT COUNT(*) as total FROM movements WHERE DATE(date) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $movements_today = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Datos para el gráfico (Últimos 7 días)
        $movement = new Movement($this->db);
        $stmt = $movement->getWeeklyMovements();
        $weekly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inicializar últimos 7 días
        $chart_labels = [];
        $chart_inputs = [];
        $chart_outputs = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = $date;
            $chart_inputs[$date] = 0;
            $chart_outputs[$date] = 0;
        }

        foreach ($weekly_data as $row) {
            $date = $row['movement_date'];
            if (isset($chart_inputs[$date])) {
                if ($row['type'] == 'entrada') {
                    $chart_inputs[$date] = $row['total_quantity'];
                } else {
                    $chart_outputs[$date] = $row['total_quantity'];
                }
            }
        }
        
        // Reindexar para enviar como array simple (values)
        $chart_inputs = array_values($chart_inputs);
        $chart_outputs = array_values($chart_outputs);

        include 'app/views/dashboard/index.php';
    }
}
?>
