<?php
class Sale {
    private $conn;
    private $table_name = "sales";
    private $details_table = "sale_details";

    public $id;
    public $user_id;
    public $total;
    public $payment_method;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una nueva venta con sus detalles
    public function create($items) {
        try {
            $this->conn->beginTransaction();

            // 1. Insertar Venta
            $query = "INSERT INTO " . $this->table_name . " (user_id, total, payment_method) VALUES (:user_id, :total, :payment_method)";
            $stmt = $this->conn->prepare($query);
            
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->total = htmlspecialchars(strip_tags($this->total));
            $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));

            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":total", $this->total);
            $stmt->bindParam(":payment_method", $this->payment_method);

            if (!$stmt->execute()) {
                throw new Exception("Error al crear la venta.");
            }

            $this->id = $this->conn->lastInsertId();

            // 2. Insertar Detalles y Actualizar Stock
            foreach ($items as $item) {
                // Insertar detalle
                $queryDetail = "INSERT INTO " . $this->details_table . " (sale_id, product_id, quantity, price, subtotal) VALUES (:sale_id, :product_id, :quantity, :price, :subtotal)";
                $stmtDetail = $this->conn->prepare($queryDetail);

                $subtotal = $item['price'] * $item['quantity'];
                
                $stmtDetail->bindParam(":sale_id", $this->id);
                $stmtDetail->bindParam(":product_id", $item['id']);
                $stmtDetail->bindParam(":quantity", $item['quantity']);
                $stmtDetail->bindParam(":price", $item['price']);
                $stmtDetail->bindParam(":subtotal", $subtotal);

                if (!$stmtDetail->execute()) {
                    throw new Exception("Error al guardar detalle de venta.");
                }

                // Actualizar Stock (Crear Movimiento de Salida)
                // Usamos la lógica de Movement para mantener consistencia
                if (!class_exists('Movement')) {
                    include_once 'app/models/Movement.php';
                }
                $movement = new Movement($this->conn);
                $movement->product_id = $item['id'];
                $movement->type = 'salida';
                $movement->quantity = $item['quantity'];
                $movement->user_id = $this->user_id;
                
                if (!$movement->create()) {
                    throw new Exception("Error al actualizar inventario.");
                }
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // echo $e->getMessage(); 
            return false;
        }
    }

    public function readOne() {
        $query = "SELECT s.id, s.date as created_at, s.total, u.username 
                  FROM " . $this->table_name . " s
                  JOIN users u ON s.user_id = u.id
                  WHERE s.id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->created_at = $row['created_at'];
            $this->total = $row['total'];
            // $this->username = $row['username']; // Si quisiéramos el usuario
            return true;
        }
        return false;
    }

    public function getDetails() {
        $query = "SELECT d.quantity, d.price, d.subtotal, p.name as product_name, p.code 
                  FROM " . $this->details_table . " d
                  JOIN products p ON d.product_id = p.id
                  WHERE d.sale_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    public function getDailyTotal() {
        $query = "SELECT SUM(total) as total_sales, COUNT(*) as count_sales 
                  FROM " . $this->table_name . " 
                  WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>