<?php
include_once 'app/config/database.php';
include_once 'app/models/Category.php';

class CategoryController {
    private $db;
    private $category;

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
        $this->category = new Category($this->db);
    }

    public function index() {
        $stmt = $this->category->read();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/categories/index.php';
    }

    public function create() {
        include 'app/views/categories/create.php';
    }

    public function store() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];

            if ($this->category->create()) {
                header("Location: index.php?page=categories");
            } else {
                echo "Error al crear categoría.";
            }
        }
    }

    public function edit($id) {
        $this->category->id = $id;
        $this->category->readOne();
        include 'app/views/categories/edit.php';
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->category->id = $_POST['id'];
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];

            if ($this->category->update()) {
                header("Location: index.php?page=categories");
            } else {
                echo "Error al actualizar categoría.";
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

        $this->category->id = $id;
        if ($this->category->delete()) {
            header("Location: index.php?page=categories");
        } else {
            echo "Error al eliminar categoría.";
        }
    }
}
?>
