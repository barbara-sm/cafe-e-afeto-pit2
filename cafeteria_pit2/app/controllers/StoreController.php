<?php
class StoreController {
    public function home() {
        $products=Product::allActive();
        $categories=Database::connection()->query('SELECT * FROM categorias ORDER BY nome')->fetchAll();
        require __DIR__.'/../views/products/home.php';
    }
    public function addToCart() {
        requirePost();
        $id=(int)($_POST['id']??0); $product=Product::find($id);
        if(!$product || !$product['ativo']) { flash('Produto indisponível.'); redirect('index.php'); }
        if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];
        $_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1; flash('Produto adicionado ao carrinho!'); redirect('index.php?action=cart');
    }
    public function cart() {
        $cart=$_SESSION['cart']??[]; $items=[]; $total=0;
        foreach($cart as $id=>$qty) { $p=Product::find($id); if($p && $p['ativo']) { $p['quantidade']=$qty; $p['subtotal']=$p['preco']*$qty; $items[]=$p; $total+=$p['subtotal']; } }
        require __DIR__.'/../views/cart/cart.php';
    }
    public function updateCart() {
        requirePost();
        foreach(($_POST['quantidade']??[]) as $id=>$qty) { $qty=(int)$qty; if($qty<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]=min($qty,20); }
        flash('Carrinho atualizado.'); redirect('index.php?action=cart');
    }
    public function checkout() { requireLogin(); require __DIR__.'/../views/cart/checkout.php'; }
    public function placeOrder() {
        requireLogin();
        requirePost(); $this->cartData($items,$total);
        if(!$items) { flash('Seu carrinho está vazio.'); redirect('index.php?action=cart'); }
        $address=trim($_POST['endereco']??''); $payment=$_POST['pagamento']??'PIX';
        if($address==='') { flash('Informe o endereço ou local de retirada.'); redirect('index.php?action=checkout'); }
        if(!in_array($payment,['PIX','Cartão na entrega','Dinheiro na entrega'],true)) $payment='PIX';
        $id=Order::create($_SESSION['user']['id'],$address,$payment,$items); $_SESSION['cart']=[];
        flash('Pedido realizado! O pagamento está marcado como simulado/pendente.'); redirect('index.php?action=my_orders');
    }
    private function cartData(&$items,&$total) {
        $items=[]; $total=0;
        foreach(($_SESSION['cart']??[]) as $id=>$qty) { $p=Product::find($id); if($p && $p['ativo']) { $items[$id]=['preco'=>(float)$p['preco'],'quantidade'=>(int)$qty]; $total += $p['preco']*$qty; } }
    }
    public function myOrders() { requireLogin(); $orders=Order::forUser($_SESSION['user']['id']); require __DIR__.'/../views/orders/mine.php'; }
}
