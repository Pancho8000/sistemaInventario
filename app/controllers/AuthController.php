<?php
include_once 'app/config/database.php';
include_once 'app/models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];

            if ($this->user->login()) {
                session_start();
                session_regenerate_id(true); // Prevenir fijación de sesión
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role'] = $this->user->role;
                header("Location: index.php?page=dashboard");
            } else {
                $error = "Usuario o contraseña incorrectos.";
                include 'app/views/auth/login.php';
            }
        } else {
            include 'app/views/auth/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?page=login");
    }
}
?>
