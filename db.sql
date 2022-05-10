CREATE DATABASE registro_vendas;

use registro_vendas;

CREATE TABLE pessoa(
	id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100) NOT NULL,
	email VARCHAR(30),
	telefone VARCHAR(20),
	data_nascimento DATE
);

CREATE TABLE funcionario(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_pessoa INT NOT NULL,
	data_admissao DATE NOT NULL,
	salario DECIMAL(12,2) NOT NULL
);

CREATE TABLE usuario (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100) NOT NULL,	 
	email VARCHAR(30) NOT NULL,
	senha VARCHAR(100),
	tipo VARCHAR(1) DEFAULT 'F'
);

ALTER TABLE funcionario ADD CONSTRAINT fk_id_pessoa FOREIGN KEY (id_pessoa) REFERENCES pessoa(id);
