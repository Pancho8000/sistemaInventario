<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $code;
    public $name;
    public $description;
    public $stock;
    public $price;
    public $category_id;
    public $is_bulk;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($from_record_num = 0, $records_per_page = 10) {
        $query = "SELECT p.id, p.code, p.name, p.description, p.stock, p.price, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  ORDER BY p.created_at DESC
                  LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function count() {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET code=:code, name=:name, description=:description, stock=:stock, price=:price, category_id=:category_id, is_bulk=:is_bulk";
        $stmt = $this->conn->prepare($query);

        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->is_bulk = htmlspecialchars(strip_tags($this->is_bulk));

        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_bulk", $this->is_bulk);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getLowStock() {
        $query = "SELECT p.id, p.code, p.name, p.stock, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.stock < 10
                  ORDER BY p.stock ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getByCode() {
        $query = "SELECT p.id, p.code, p.name, p.description, p.stock, p.price, p.category_id, p.is_bulk, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.code = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->code = htmlspecialchars(strip_tags($this->code));
        $stmt->bindParam(1, $this->code);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->stock = $row['stock'];
            $this->price = $row['price'];
            $this->category_id = $row['category_id'];
            $this->is_bulk = $row['is_bulk'];
            return true;
        }
        return false;
    }

    public function search($keywords, $from_record_num = 0, $records_per_page = 10) {
        $query = "SELECT p.id, p.code, p.name, p.description, p.stock, p.price, p.is_bulk, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.name LIKE ? OR p.code LIKE ? OR p.description LIKE ?
                  ORDER BY p.created_at DESC
                  LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(5, $records_per_page, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }

    public function countSearch($keywords) {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " p
                  WHERE p.name LIKE ? OR p.code LIKE ? OR p.description LIKE ?";
        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function readOne() {
        $query = "SELECT p.id, p.code, p.name, p.description, p.stock, p.price, p.category_id, p.is_bulk, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->code = $row['code'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->stock = $row['stock'];
            $this->price = $row['price'];
            $this->category_id = $row['category_id'];
            $this->is_bulk = $row['is_bulk'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET code=:code, name=:name, description=:description, price=:price, category_id=:category_id, is_bulk=:is_bulk
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->is_bulk = htmlspecialchars(strip_tags($this->is_bulk));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":code", $this->code);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":is_bulk", $this->is_bulk);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
