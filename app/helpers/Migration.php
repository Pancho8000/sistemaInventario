<?php
class Migration {
    public static function run($db) {
        try {
            // 1. Tabla Settings
            $sql = "CREATE TABLE IF NOT EXISTS settings (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->exec($sql);

            // Datos por defecto Settings
            $defaults = [
                'company_name' => 'Mi Empresa S.A.',
                'company_address' => 'Av. Principal 123',
                'company_phone' => '555-1234',
                'currency_symbol' => '$',
                'tax_rate' => '16'
            ];
            foreach ($defaults as $key => $val) {
                $stmt = $db->prepare("SELECT COUNT(*) FROM settings WHERE name = ?");
                $stmt->execute([$key]);
                if ($stmt->fetchColumn() == 0) {
                    $stmt = $db->prepare("INSERT INTO settings (name, value) VALUES (?, ?)");
                    $stmt->execute([$key, $val]);
                }
            }

            // 2. Tabla Sales
            $sql = "CREATE TABLE IF NOT EXISTS sales (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                payment_method VARCHAR(50) DEFAULT 'efectivo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->exec($sql);

            // 3. Tabla Sale Details
            $sql = "CREATE TABLE IF NOT EXISTS sale_details (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                sale_id INT(11) NOT NULL,
                product_id INT(11) NOT NULL,
                quantity INT(11) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                subtotal DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (sale_id) REFERENCES sales(id),
                FOREIGN KEY (product_id) REFERENCES products(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->exec($sql);

        } catch (PDOException $e) {
            // Silencioso o log, para no romper el flujo si ya existen
            error_log("Migration Error: " . $e->getMessage());
        }
    }
}
?>