<?php
include_once 'app/config/database.php';
include_once 'app/models/Setting.php';

class SettingController {
    private $db;
    private $setting;

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
        $this->setting = new Setting($this->db);
    }

    public function index() {
        $settings = $this->setting->getAll();
        include 'app/views/settings/index.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            // Filtrar campos que no son settings
            $data = $_POST;
            unset($data['csrf_token']);

            if ($this->setting->update($data)) {
                $success = "Configuración actualizada correctamente.";
            } else {
                $error = "Error al actualizar la configuración.";
            }
            
            // Recargar datos
            $settings = $this->setting->getAll();
            include 'app/views/settings/index.php';
        }
    }
    
    public function backup() {
        // Lógica de Backup simple (Dump de la BD)
        $database = new Database();
        // Nota: Para un backup real se necesitaría mysqldump o una librería, 
        // aquí haremos una exportación básica o simulada.
        // Dado que mysqldump depende del path del sistema, haremos una implementación PHP básica.
        
        $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . $backup_file . "\""); 
        
        // Obtener todas las tablas
        $tables = array();
        $stmt = $this->db->query('SHOW TABLES');
        while($row = $stmt->fetch(PDO::FETCH_NUM)){
            $tables[] = $row[0];
        }
        
        $return = "";
        
        foreach($tables as $table){
            $result = $this->db->query('SELECT * FROM '.$table);
            $num_fields = $result->columnCount();
            
            $return .= 'DROP TABLE IF EXISTS '.$table.';';
            $row2 = $this->db->query('SHOW CREATE TABLE '.$table)->fetch(PDO::FETCH_NUM);
            $return .= "\n\n".$row2[1].";\n\n";
            
            for ($i = 0; $i < $num_fields; $i++) {
                while($row = $result->fetch(PDO::FETCH_NUM)){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= 'NULL'; }
                    if ($j < ($num_fields-1)) { $return.= ','; }
                }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }
        
        echo $return;
        exit;
    }
}
?>