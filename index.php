<?php
// Configuración de seguridad de sesiones
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict'); // O 'Lax' si es necesario
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

session_start();

include_once 'app/helpers/Csrf.php';

// Auto-migration check
include_once 'app/config/database.php';
include_once 'app/helpers/Migration.php';
$database = new Database();
$db = $database->getConnection();
if ($db) {
    Migration::run($db);
}

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Rutas permitidas sin autenticación
$public_pages = ['login'];

if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages)) {
    header("Location: index.php?page=login");
    exit();
}

switch ($page) {
    case 'login':
        include_once 'app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->login();
        break;
    case 'logout':
        include_once 'app/controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        break;
    case 'dashboard':
        include_once 'app/controllers/DashboardController.php';
        $dashboard = new DashboardController();
        $dashboard->index();
        break;
    case 'products':
        include_once 'app/controllers/ProductController.php';
        $productController = new ProductController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action == 'create') $productController->create();
        elseif ($action == 'store') $productController->store();
        elseif ($action == 'edit') $productController->edit($_GET['id']);
        elseif ($action == 'update') $productController->update();
        elseif ($action == 'delete') $productController->delete($_GET['id']);
        elseif ($action == 'low_stock') $productController->lowStock();
        elseif ($action == 'export') $productController->export();
        elseif ($action == 'barcode') $productController->barcode($_GET['id']);
        elseif ($action == 'show') $productController->show($_GET['id']);
        else $productController->index();
        break;
    case 'movements':
        include_once 'app/controllers/MovementController.php';
        $movementController = new MovementController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'create';
        if ($action == 'create') $movementController->create();
        elseif ($action == 'store') $movementController->store();
        elseif ($action == 'history') $movementController->history();
        elseif ($action == 'scan') $movementController->scan();
        elseif ($action == 'export') $movementController->export();
        break;
    case 'categories':
        include_once 'app/controllers/CategoryController.php';
        $categoryController = new CategoryController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action == 'create') $categoryController->create();
        elseif ($action == 'store') $categoryController->store();
        elseif ($action == 'edit') $categoryController->edit($_GET['id']);
        elseif ($action == 'update') $categoryController->update();
        elseif ($action == 'delete') $categoryController->delete($_GET['id']);
        else $categoryController->index();
        break;
    case 'suppliers':
        include_once 'app/controllers/SupplierController.php';
        $supplierController = new SupplierController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action == 'create') $supplierController->create();
        elseif ($action == 'store') $supplierController->store();
        elseif ($action == 'edit') $supplierController->edit($_GET['id']);
        elseif ($action == 'update') $supplierController->update();
        elseif ($action == 'delete') $supplierController->delete($_GET['id']);
        else $supplierController->index();
        break;
    case 'users':
        include_once 'app/controllers/UserController.php';
        $userController = new UserController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action == 'create') $userController->create();
        elseif ($action == 'store') $userController->store();
        elseif ($action == 'delete') $userController->delete($_GET['id']);
        elseif ($action == 'profile') $userController->profile();
        elseif ($action == 'update_password') $userController->update_password();
        else $userController->index();
        break;
    case 'settings':
        include_once 'app/controllers/SettingController.php';
        $settingController = new SettingController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($action == 'update') $settingController->update();
        elseif ($action == 'backup') $settingController->backup();
        else $settingController->index();
        break;
    case 'sales':
        include_once 'app/controllers/SaleController.php';
        $saleController = new SaleController();
        $action = isset($_GET['action']) ? $_GET['action'] : 'create';
        if ($action == 'create') $saleController->create();
        elseif ($action == 'store') $saleController->store();
        elseif ($action == 'search_product') $saleController->search_product();
        elseif ($action == 'ticket') $saleController->ticket($_GET['id']);
        elseif ($action == 'daily') $saleController->daily_report();
        break;
    case 'setup':
        include_once 'app/controllers/SetupController.php';
        $setup = new SetupController();
        $setup->index();
        break;
    default:
        echo "Página no encontrada";
        break;
}
?>
