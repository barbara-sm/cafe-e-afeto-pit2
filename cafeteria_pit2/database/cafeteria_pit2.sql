CREATE DATABASE IF NOT EXISTS cafeteria_pit2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafeteria_pit2;

CREATE TABLE usuarios (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 telefone VARCHAR(30),
 senha VARCHAR(255) NOT NULL,
 tipo ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE produtos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 categoria_id INT NOT NULL,
 nome VARCHAR(120) NOT NULL,
 descricao TEXT,
 preco DECIMAL(10,2) NOT NULL,
 imagem VARCHAR(500),
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_produto_categoria FOREIGN KEY(categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE pedidos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 cliente_id INT NOT NULL,
 endereco_entrega VARCHAR(500) NOT NULL,
 forma_pagamento ENUM('PIX','Cartão na entrega','Dinheiro na entrega') NOT NULL,
 total DECIMAL(10,2) NOT NULL,
 status ENUM('Pendente','Preparando','Pronto','Concluído','Cancelado') NOT NULL DEFAULT 'Pendente',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pedido_cliente FOREIGN KEY(cliente_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE itens_pedido (
 id INT AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT NOT NULL,
 produto_id INT NOT NULL,
 quantidade INT NOT NULL,
 preco_unitario DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_item_pedido FOREIGN KEY(pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
 CONSTRAINT fk_item_produto FOREIGN KEY(produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;

CREATE TABLE pagamentos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT NOT NULL,
 metodo VARCHAR(50) NOT NULL,
 valor DECIMAL(10,2) NOT NULL,
 status ENUM('Aguardando','Aprovado','Recusado') NOT NULL DEFAULT 'Aguardando',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pagamento_pedido FOREIGN KEY(pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO categorias(nome) VALUES ('Cafés'),('Bebidas geladas'),('Doces'),('Salgados');
INSERT INTO produtos(categoria_id,nome,descricao,preco,imagem,ativo) VALUES
(1,'Espresso','Café intenso, extraído na hora.',6.50,'',1),
(1,'Cappuccino','Café espresso com leite vaporizado e toque de canela.',10.90,'',1),
(1,'Mocha','Café espresso, leite e chocolate.',12.50,'',1),
(2,'Iced Latte','Espresso com leite gelado e gelo.',11.00,'',1),
(3,'Brownie','Brownie de chocolate com casquinha crocante.',9.50,'',1),
(3,'Cheesecake','Fatia de cheesecake com calda de frutas vermelhas.',14.00,'',1),
(4,'Pão de queijo','Porção com quatro unidades.',8.00,'',1),
(4,'Croissant','Croissant amanteigado assado diariamente.',9.90,'',1);
