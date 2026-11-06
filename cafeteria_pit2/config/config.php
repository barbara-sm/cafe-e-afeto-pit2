<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cafeteria_pit2');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'Café & Afeto');
define('BASE_URL', '');

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}
function verifyCsrf() {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrfToken(), $sent)) {
        http_response_code(403);
        exit('Não foi possível confirmar o envio do formulário. Atualize a página e tente novamente.');
    }
}
function requirePost() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Esta ação precisa ser enviada por formulário.');
    }
    verifyCsrf();
}
function redirect($url) { header('Location: ' . $url); exit; }
function isLoggedIn() { return !empty($_SESSION['user']); }
function isAdmin() { return isLoggedIn() && ($_SESSION['user']['tipo'] ?? '') === 'admin'; }
function requireLogin() { if (!isLoggedIn()) redirect('index.php?action=login'); }
function requireAdmin() { if (!isAdmin()) { http_response_code(403); exit('Acesso restrito à administração.'); } }
function flash($message = null) {
    if ($message !== null) { $_SESSION['flash'] = $message; return; }
    $msg = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']); return $msg;
}
