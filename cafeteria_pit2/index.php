<?php
// Cookie de sessão com configurações adequadas para execução local.
$isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => $isHttps,
]);
session_start();
require __DIR__ . '/config/config.php';
require __DIR__ . '/app/models/Database.php';
require __DIR__ . '/app/models/Product.php';
require __DIR__ . '/app/models/Order.php';
require __DIR__ . '/app/controllers/StoreController.php';
require __DIR__ . '/app/controllers/AuthController.php';
require __DIR__ . '/app/controllers/AdminController.php';

$action = $_GET['action'] ?? 'home';
$store = new StoreController();
$auth = new AuthController();
$admin = new AdminController();

switch ($action) {
    case 'home': case 'menu': $store->home(); break;
    case 'add': $store->addToCart(); break;
    case 'cart': $store->cart(); break;
    case 'update_cart': $store->updateCart(); break;
    case 'checkout': $store->checkout(); break;
    case 'place_order': $store->placeOrder(); break;
    case 'my_orders': $store->myOrders(); break;
    case 'register': $auth->register(); break;
    case 'login': $auth->login(); break;
    case 'logout': $auth->logout(); break;
    case 'admin': $admin->dashboard(); break;
    case 'admin_edit': $admin->editProduct(); break;
    case 'admin_product': $admin->saveProduct(); break;
    case 'admin_delete': $admin->deleteProduct(); break;
    case 'admin_status': $admin->updateStatus(); break;
    default: http_response_code(404); echo 'Página não encontrada.';
}
