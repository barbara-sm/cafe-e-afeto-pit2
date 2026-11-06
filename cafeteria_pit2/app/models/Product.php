<?php
class Product {
    public static function allActive() {
        return Database::connection()->query("SELECT p.*, c.nome AS categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.ativo=1 ORDER BY c.nome,p.nome")->fetchAll();
    }
    public static function all() {
        return Database::connection()->query("SELECT p.*, c.nome AS categoria FROM produtos p LEFT JOIN categorias c ON c.id=p.categoria_id ORDER BY p.id DESC")->fetchAll();
    }
    public static function find($id) {
        $s=Database::connection()->prepare('SELECT * FROM produtos WHERE id=?'); $s->execute([$id]); return $s->fetch();
    }
    public static function save($data) {
        $pdo=Database::connection();
        if (!empty($data['id'])) {
            $s=$pdo->prepare('UPDATE produtos SET categoria_id=?,nome=?,descricao=?,preco=?,imagem=?,ativo=? WHERE id=?');
            $s->execute([$data['categoria_id'],$data['nome'],$data['descricao'],$data['preco'],$data['imagem'],$data['ativo'],$data['id']]);
        } else {
            $s=$pdo->prepare('INSERT INTO produtos(categoria_id,nome,descricao,preco,imagem,ativo) VALUES(?,?,?,?,?,?)');
            $s->execute([$data['categoria_id'],$data['nome'],$data['descricao'],$data['preco'],$data['imagem'],$data['ativo']]);
        }
    }
    public static function delete($id) { $s=Database::connection()->prepare('UPDATE produtos SET ativo=0 WHERE id=?'); $s->execute([$id]); }
}
