CREATE DATABASE registro_vendas;

use registro_vendas;

CREATE TABLE pessoa(
	id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100) NOT NULL,
	email VARCHAR(30),
	telefone VARCHAR(20),
	data_nascimento DATE
);

CREATE TABLE usuario (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100) NOT NULL,	 
	email VARCHAR(30) NOT NULL UNIQUE,
	senha VARCHAR(100),
	tipo VARCHAR(1) DEFAULT 'F'
);

CREATE TABLE produto (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	descricao VARCHAR(120) NOT NULL,
	quantidade DECIMAL(13,3) DEFAULT 0,
	valor_venda DECIMAL(12,2) DEFAULT 0,
	valor_compra DECIMAL(12,2) DEFAULT 0,
	unidade_medida VARCHAR(5) NOT NULL
);

CREATE TABLE funcionario(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_pessoa INT NOT NULL,
	data_admissao DATE NOT NULL,
	salario DECIMAL(12,2) NOT NULL
);

CREATE TABLE cliente (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	endereco VARCHAR(30),
	uf CHAR(2),
	cidade VARCHAR(30),
	pais VARCHAR(30),
	bairro VARCHAR(30),
	id_pessoa INT NOT NULL
);

CREATE TABLE venda (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	data_venda DATETIME NOT NULL,
	observacao VARCHAR(200),
	id_cliente INT NOT NULL,
	id_usuario INT NOT NULL,
	id_funcionario INT NOT NULL
);

CREATE TABLE venda_produto (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	quantidade DECIMAL(13,3) NOT NULL,
	valor_unitario DECIMAL(12,2) NOT NULL,
	id_venda INT NOT NULL,
	id_produto INT NOT NULL
);

ALTER TABLE funcionario ADD CONSTRAINT fk_id_funcionario_pessoa FOREIGN KEY (id_pessoa) REFERENCES pessoa(id);

ALTER TABLE cliente ADD CONSTRAINT fk_id_cliente_pessoa FOREIGN KEY (id_pessoa) REFERENCES pessoa(id);

ALTER TABLE venda ADD CONSTRAINT fk_id_venda_cliente FOREIGN KEY (id_cliente) REFERENCES cliente(id);
ALTER TABLE venda ADD CONSTRAINT fk_id_venda_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id);
ALTER TABLE venda ADD CONSTRAINT fk_id_venda_funcionario FOREIGN KEY (id_funcionario) REFERENCES funcionario(id);

ALTER TABLE venda_produto ADD CONSTRAINT fk_id_venda_produto_venda FOREIGN KEY (id_venda) REFERENCES venda(id) ON DELETE CASCADE;
ALTER TABLE venda_produto ADD CONSTRAINT fk_id_venda_produto_produto FOREIGN KEY (id_produto) REFERENCES produto(id);

INSERT INTO usuario (nome, email, senha, tipo) VALUES ('Bruno Bevilaqua', 'bbbevilaqua@gmail.com', '202cb962ac59075b964b07152d234b70', 'A');
