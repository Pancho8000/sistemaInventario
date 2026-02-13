<?php
class Setting {
    private $conn;
    private $table_name = "settings";

    public $id;
    public $name;
    public $value;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT name, value FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['name']] = $row['value'];
        }
        return $settings;
    }

    public function update($settings) {
        $query = "UPDATE " . $this->table_name . " SET value = :value WHERE name = :name";
        $stmt = $this->conn->prepare($query);

        foreach ($settings as $name => $value) {
            $name = htmlspecialchars(strip_tags($name));
            $value = htmlspecialchars(strip_tags($value));
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
        }
        return true;
    }
}
?>