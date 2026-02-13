<?php
include_once 'app/config/database.php';

class SetupController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        if ($this->db === null) {
            echo "Error: No se pudo conectar a la base de datos.";
            return;
        }

        $tables = [];

        // 1. Settings
        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        try {
            $this->db->exec($sql);
            $tables[] = "Tabla 'settings' verificada/creada.";
            
            // Default settings
            $defaults = [
                'company_name' => 'Mi Empresa S.A.',
                'company_address' => 'Av. Principal 123',
                'company_phone' => '555-1234',
                'currency_symbol' => '$',
                'tax_rate' => '16'
            ];
            foreach ($defaults as $key => $val) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM settings WHERE name = ?");
                $stmt->execute([$key]);
                if ($stmt->fetchColumn() == 0) {
                    $stmt = $this->db->prepare("INSERT INTO settings (name, value) VALUES (?, ?)");
                    $stmt->execute([$key, $val]);
                }
            }
        } catch (PDOException $e) {
            $tables[] = "Error en settings: " . $e->getMessage();
        }

        // 2. Sales
        $sql = "CREATE TABLE IF NOT EXISTS sales (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) DEFAULT 'efectivo',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        try {
            $this->db->exec($sql);
            $tables[] = "Tabla 'sales' verificada/creada.";
        } catch (PDOException $e) {
            $tables[] = "Error en sales: " . $e->getMessage();
        }

        // 3. Sale Details
        $sql = "CREATE TABLE IF NOT EXISTS sale_details (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            sale_id INT(11) NOT NULL,
            product_id INT(11) NOT NULL,
            quantity DECIMAL(10,3) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (sale_id) REFERENCES sales(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        try {
            $this->db->exec($sql);
            $tables[] = "Tabla 'sale_details' verificada/creada.";
        } catch (PDOException $e) {
            $tables[] = "Error en sale_details: " . $e->getMessage();
        }

        // 4. Products & Movements Updates (Granel)
        try {
            // Verificar si products existe para alterarla
            $this->db->exec("ALTER TABLE products MODIFY stock DECIMAL(10,3)");
            
            // Verificar si columna is_bulk existe
            $col = $this->db->query("SHOW COLUMNS FROM products LIKE 'is_bulk'");
            if($col->rowCount() == 0) {
                $this->db->exec("ALTER TABLE products ADD COLUMN is_bulk TINYINT(1) DEFAULT 0");
                $tables[] = "Columna 'is_bulk' agregada a products.";
            }

            // Movements
            $this->db->exec("ALTER TABLE movements MODIFY quantity DECIMAL(10,3)");
             // Sale Details (por si ya existía como INT)
            $this->db->exec("ALTER TABLE sale_details MODIFY quantity DECIMAL(10,3)");

            $tables[] = "Tablas actualizadas para soporte decimal (Granel).";
        } catch (PDOException $e) {
            // Ignorar errores si ya están modificadas o no existen
            $tables[] = "Nota sobre actualizaciones: " . $e->getMessage();
        }

        // Mostrar resultados
        echo "<h1>Mantenimiento de Base de Datos</h1>";
        echo "<ul>";
        foreach ($tables as $msg) {
            echo "<li>$msg</li>";
        }
        echo "</ul>";
        echo "<a href='index.php?page=dashboard'>Volver al Dashboard</a>";
    }
}
?>