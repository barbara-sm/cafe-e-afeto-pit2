<?php
class AdminController {
    public function dashboard() {
        requireAdmin(); $products=Product::all(); $orders=Order::all();
        $stats=Database::connection()->query("SELECT COUNT(*) pedidos, COALESCE(SUM(CASE WHEN status='Concluído' THEN total ELSE 0 END),0) vendas FROM pedidos")->fetch();
        $categories=Database::connection()->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
        $editProduct=!empty($_GET['edit']) ? Product::find((int)$_GET['edit']) : null;
        require __DIR__.'/../views/admin/dashboard.php';
    }
    public function saveProduct() {
        requireAdmin();
        requirePost();
        if($_SERVER['REQUEST_METHOD']==='POST') {
            $name=trim($_POST['nome']??''); $price=(float)str_replace(',','.',$_POST['preco']??0);
            if(!$name || $price<=0) { flash('Informe nome e preço válido.'); redirect('index.php?action=admin'); }
            Product::save(['id'=>(int)($_POST['id']??0),'categoria_id'=>(int)$_POST['categoria_id'],'nome'=>$name,'descricao'=>trim($_POST['descricao']??''),'preco'=>$price,'imagem'=>trim($_POST['imagem']??''),'ativo'=>isset($_POST['ativo'])?1:0]);
            flash('Produto salvo.');
        }
        redirect('index.php?action=admin');
    }
    public function editProduct() { requireAdmin(); redirect('index.php?action=admin&edit='.(int)($_GET['id']??0)); }
    public function deleteProduct() { requireAdmin(); requirePost(); Product::delete((int)($_POST['id']??0)); flash('Produto desativado e removido do cardápio.'); redirect('index.php?action=admin'); }
    public function updateStatus() {
        requireAdmin(); requirePost(); $allowed=['Pendente','Preparando','Pronto','Concluído','Cancelado']; $status=$_POST['status']??'Pendente';
        if(in_array($status,$allowed,true)) Order::status((int)$_POST['id'],$status); flash('Status do pedido atualizado.'); redirect('index.php?action=admin');
    }
}
