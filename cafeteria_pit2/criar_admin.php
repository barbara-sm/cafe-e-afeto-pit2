<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/app/models/Database.php';

session_start();
$mensagem = '';
$pdo = Database::connection();
$quantidade = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    if ($quantidade > 0) {
        $mensagem = 'Já existe uma conta administrativa. Apague este arquivo.';
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        if ($nome === '' || !$email || strlen($senha) < 8) {
            $mensagem = 'Informe nome, e-mail válido e senha com pelo menos 8 caracteres.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone, senha, tipo) VALUES (?, ?, '', ?, 'admin')");
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
            $mensagem = 'Conta administrativa criada. Apague o arquivo criar_admin.php antes de continuar.';
            $quantidade = 1;
        }
    }
}
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Criar administrador</title><link rel="stylesheet" href="public/assets/style.css"></head>
<body><main class="container"><section class="form-wrap"><h1>Criar acesso administrativo</h1>
<?php if ($mensagem): ?><p class="notice"><?= e($mensagem) ?></p><?php endif; ?>
<?php if ($quantidade === 0): ?>
<p>Use esta página uma única vez para cadastrar a pessoa responsável pela administração do sistema.</p>
<form method="post"><?= csrfField() ?>
<label>Nome<input name="nome" required></label>
<label>E-mail<input type="email" name="email" required></label>
<label>Senha (mínimo de 8 caracteres)<input type="password" name="senha" minlength="8" required></label>
<button class="btn" type="submit">Criar administrador</button></form>
<?php else: ?><p>Por segurança, exclua o arquivo <strong>criar_admin.php</strong> da pasta do projeto.</p><?php endif; ?>
</section></main></body></html>
