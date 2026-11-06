<?php
class AuthController {
    public function register() {
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verifyCsrf();
            $name=trim($_POST['nome']??''); $email=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL); $phone=trim($_POST['telefone']??''); $pass=$_POST['senha']??'';
            if(!$name || !$email || strlen($pass)<6) { flash('Preencha os dados corretamente. A senha precisa ter pelo menos 6 caracteres.'); redirect('index.php?action=register'); }
            $pdo=Database::connection(); $check=$pdo->prepare('SELECT id FROM usuarios WHERE email=?'); $check->execute([$email]);
            if($check->fetch()) { flash('Esse e-mail já está cadastrado.'); redirect('index.php?action=register'); }
            $s=$pdo->prepare("INSERT INTO usuarios(nome,email,telefone,senha,tipo) VALUES(?,?,?,?, 'cliente')"); $s->execute([$name,$email,$phone,password_hash($pass,PASSWORD_DEFAULT)]);
            flash('Cadastro realizado! Agora faça seu login.'); redirect('index.php?action=login');
        }
        require __DIR__.'/../views/auth/register.php';
    }
    public function login() {
        if($_SERVER['REQUEST_METHOD']==='POST') {
            verifyCsrf();
            $s=Database::connection()->prepare('SELECT * FROM usuarios WHERE email=?'); $s->execute([trim($_POST['email']??'')]); $u=$s->fetch();
            if($u && password_verify($_POST['senha']??'',$u['senha'])) { session_regenerate_id(true); $_SESSION['user']=['id'=>$u['id'],'nome'=>$u['nome'],'email'=>$u['email'],'tipo'=>$u['tipo']]; redirect('index.php'); }
            flash('E-mail ou senha incorretos.'); redirect('index.php?action=login');
        }
        require __DIR__.'/../views/auth/login.php';
    }
    public function logout() { requirePost(); $_SESSION=[]; session_destroy(); redirect('index.php'); }
}
