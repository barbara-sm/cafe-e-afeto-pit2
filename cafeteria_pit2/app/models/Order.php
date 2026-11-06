<?php
class Order {
    public static function create($userId,$address,$payment,$items) {
        $pdo=Database::connection(); $pdo->beginTransaction();
        try {
            $total=0;
            foreach($items as $item) $total += $item['preco']*$item['quantidade'];
            $s=$pdo->prepare("INSERT INTO pedidos(cliente_id,endereco_entrega,forma_pagamento,total,status) VALUES(?,?,?,?, 'Pendente')");
            $s->execute([$userId,$address,$payment,$total]); $orderId=$pdo->lastInsertId();
            $line=$pdo->prepare('INSERT INTO itens_pedido(pedido_id,produto_id,quantidade,preco_unitario) VALUES(?,?,?,?)');
            foreach($items as $id=>$item) $line->execute([$orderId,$id,$item['quantidade'],$item['preco']]);
            $pay=$pdo->prepare("INSERT INTO pagamentos(pedido_id,metodo,valor,status) VALUES(?,?,?,'Aguardando')");
            $pay->execute([$orderId,$payment,$total]); $pdo->commit(); return $orderId;
        } catch(Throwable $e) { $pdo->rollBack(); throw $e; }
    }
    public static function forUser($id) {
        $s=Database::connection()->prepare('SELECT p.*, (SELECT COUNT(*) FROM itens_pedido i WHERE i.pedido_id=p.id) AS quantidade_itens FROM pedidos p WHERE cliente_id=? ORDER BY p.criado_em DESC'); $s->execute([$id]); return $s->fetchAll();
    }
    public static function all() {
        return Database::connection()->query('SELECT p.*,u.nome AS cliente,u.email FROM pedidos p JOIN usuarios u ON u.id=p.cliente_id ORDER BY p.criado_em DESC')->fetchAll();
    }
    public static function details($id) {
        $s=Database::connection()->prepare('SELECT i.*,p.nome FROM itens_pedido i JOIN produtos p ON p.id=i.produto_id WHERE i.pedido_id=?'); $s->execute([$id]); return $s->fetchAll();
    }
    public static function status($id,$status) {
        $s=Database::connection()->prepare('UPDATE pedidos SET status=? WHERE id=?'); $s->execute([$status,$id]);
    }
}
