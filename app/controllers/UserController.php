<?php
include_once 'app/config/database.php';
include_once 'app/models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    private function checkAdmin() {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public function index() {
        $this->checkAdmin();
        $stmt = $this->user->read();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/users/index.php';
    }

    public function create() {
        $this->checkAdmin();
        include 'app/views/users/create.php';
    }

    public function store() {
        $this->checkAdmin();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];
            $this->user->role = $_POST['role'];

            if ($this->user->create()) {
                header("Location: index.php?page=users");
            } else {
                echo "Error al crear usuario.";
            }
        }
    }

    public function delete($id) {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Método no permitido.");
        }
        if (!isset($_POST['csrf_token']) || !Csrf::verifyToken($_POST['csrf_token'])) {
            die("Error de validación CSRF");
        }

        $this->user->id = $id;
        // Evitar que un usuario se elimine a sí mismo (validación básica)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SESSION['user_id'] == $id) {
            echo "No puedes eliminar tu propia cuenta.";
            return;
        }

        if ($this->user->delete()) {
            header("Location: index.php?page=users");
        } else {
            echo "Error al eliminar usuario.";
        }
    }

    public function profile() {
        $this->user->id = $_SESSION['user_id'];
        if ($this->user->readOne()) {
            $_SESSION['username'] = $this->user->username;
            $_SESSION['role'] = $this->user->role;
        }
        include 'app/views/users/profile.php';
    }

    public function update_password() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            if ($_POST['password'] !== $_POST['confirm_password']) {
                $error = "Las contraseñas no coinciden.";
                include 'app/views/users/profile.php';
                return;
            }

            $this->user->id = $_SESSION['user_id'];
            if ($this->user->updatePassword($_POST['password'])) {
                $success = "Contraseña actualizada correctamente.";
            } else {
                $error = "Error al actualizar la contraseña.";
            }
            include 'app/views/users/profile.php';
        }
    }
}
?>