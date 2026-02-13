<?php
include_once 'app/config/database.php';
include_once 'app/models/Supplier.php';

class SupplierController {
    private $db;
    private $supplier;

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
        $this->supplier = new Supplier($this->db);
    }

    public function index() {
        $stmt = $this->supplier->read();
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/suppliers/index.php';
    }

    public function create() {
        include 'app/views/suppliers/create.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->supplier->name = $_POST['name'];
            $this->supplier->email = $_POST['email'];
            $this->supplier->phone = $_POST['phone'];
            $this->supplier->address = $_POST['address'];

            if ($this->supplier->create()) {
                header("Location: index.php?page=suppliers");
            } else {
                echo "Error al crear proveedor.";
            }
        }
    }

    public function edit($id) {
        $this->supplier->id = $id;
        $this->supplier->readOne();
        include 'app/views/suppliers/edit.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->supplier->id = $_POST['id'];
            $this->supplier->name = $_POST['name'];
            $this->supplier->email = $_POST['email'];
            $this->supplier->phone = $_POST['phone'];
            $this->supplier->address = $_POST['address'];

            if ($this->supplier->update()) {
                header("Location: index.php?page=suppliers");
            } else {
                echo "Error al actualizar proveedor.";
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Método no permitido.");
        }
        if (!isset($_POST['csrf_token']) || !Csrf::verifyToken($_POST['csrf_token'])) {
            die("Error de validación CSRF");
        }

        $this->supplier->id = $id;
        if ($this->supplier->delete()) {
            header("Location: index.php?page=suppliers");
        } else {
            echo "Error al eliminar proveedor.";
        }
    }
}
?>
