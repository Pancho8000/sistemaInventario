<?php
class Movement {
    private $conn;
    private $table_name = "movements";

    public $product_id;
    public $type;
    public $quantity;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // 1. Registrar movimiento
        $query = "INSERT INTO " . $this->table_name . " SET product_id=:product_id, type=:type, quantity=:quantity, user_id=:user_id";
        $stmt = $this->conn->prepare($query);

        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":user_id", $this->user_id);

        if ($stmt->execute()) {
            // 2. Actualizar stock del producto
            return $this->updateProductStock();
        }
        return false;
    }

    private function updateProductStock() {
        $operator = ($this->type == 'entrada') ? '+' : '-';
        $query = "UPDATE products SET stock = stock " . $operator . " :quantity WHERE id = :product_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":product_id", $this->product_id);
        
        return $stmt->execute();
    }

    public function history() {
        $query = "SELECT m.id, m.type, m.quantity, m.date, p.name as product_name, p.code, u.username 
                  FROM " . $this->table_name . " m
                  JOIN products p ON m.product_id = p.id
                  LEFT JOIN users u ON m.user_id = u.id
                  ORDER BY m.date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getHistoryByProduct($product_id) {
        $query = "SELECT m.id, m.type, m.quantity, m.date, u.username 
                  FROM " . $this->table_name . " m
                  LEFT JOIN users u ON m.user_id = u.id
                  WHERE m.product_id = :product_id
                  ORDER BY m.date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();
        return $stmt;
    }

    public function getWeeklyMovements() {
        $query = "SELECT DATE(date) as movement_date, type, SUM(quantity) as total_quantity
                  FROM " . $this->table_name . "
                  WHERE date >= CURDATE() - INTERVAL 6 DAY
                  GROUP BY DATE(date), type
                  ORDER BY movement_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
