<?php
include_once 'app/config/database.php';
include_once 'app/models/Product.php';

class ProductController {
    private $db;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    private function checkAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?page=products");
            exit;
        }
    }

    public function index() {
        // Paginación
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $records_per_page = 5;
        $from_record_num = ($records_per_page * $page) - $records_per_page;

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $keywords = $_GET['search'];
            $stmt = $this->product->search($keywords, $from_record_num, $records_per_page);
            $total_rows = $this->product->countSearch($keywords);
        } else {
            $stmt = $this->product->read($from_record_num, $records_per_page);
            $total_rows = $this->product->count();
        }
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_pages = ceil($total_rows / $records_per_page);
        
        include 'app/views/products/index.php';
    }

    public function lowStock() {
        $stmt = $this->product->getLowStock();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/products/low_stock.php';
    }

    public function create() {
        $this->checkAdmin();
        include_once 'app/models/Category.php';
        $category = new Category($this->db);
        $stmt = $category->read();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'app/views/products/create.php';
    }

    public function store() {
        $this->checkAdmin();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->product->code = $_POST['code'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->stock = $_POST['stock'];
            $this->product->price = $_POST['price'];
            $this->product->category_id = isset($_POST['category_id']) ? $_POST['category_id'] : NULL;
            $this->product->is_bulk = isset($_POST['is_bulk']) ? 1 : 0;

            if ($this->product->create()) {
                header("Location: index.php?page=products");
            } else {
                echo "Error al crear producto.";
            }
        }
    }

    public function edit($id) {
        $this->checkAdmin();
        $this->product->id = $id;
        $this->product->readOne();

        include_once 'app/models/Category.php';
        $category = new Category($this->db);
        $stmt = $category->read();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/products/edit.php';
    }

    public function update() {
        $this->checkAdmin();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!Csrf::verifyToken($_POST['csrf_token'])) {
                die("Error de validación CSRF");
            }

            $this->product->id = $_POST['id'];
            $this->product->code = $_POST['code'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->price = $_POST['price'];
            $this->product->category_id = isset($_POST['category_id']) ? $_POST['category_id'] : NULL;
            $this->product->is_bulk = isset($_POST['is_bulk']) ? 1 : 0;

            if ($this->product->update()) {
                header("Location: index.php?page=products");
            } else {
                echo "Error al actualizar producto.";
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

        $this->product->id = $id;
        if ($this->product->delete()) {
            header("Location: index.php?page=products");
        } else {
            echo "Error al eliminar producto.";
        }
    }

    public function export() {
        $stmt = $this->product->read();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = "inventario_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Encabezados (BOM para Excel)
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputcsv($output, array('Código', 'Nombre', 'Descripción', 'Categoría', 'Stock', 'Precio'));

        foreach ($products as $product) {
            fputcsv($output, array(
                $product['code'],
                $product['name'],
                $product['description'],
                $product['category_name'],
                $product['stock'],
                $product['price']
            ));
        }

        fclose($output);
        exit();
    }

    public function barcode($id) {
        $this->product->id = $id;
        $this->product->readOne();
        include 'app/views/products/barcode.php';
    }

    public function show($id) {
        $this->product->id = $id;
        $this->product->readOne();

        include_once 'app/models/Movement.php';
        $movementModel = new Movement($this->db);
        $stmt = $movementModel->getHistoryByProduct($id);
        $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'app/views/products/show.php';
    }
}
?>
