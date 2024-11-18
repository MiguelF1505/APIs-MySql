create database loja;
use loja;

create table if not exists Setores(
id_setor int primary key not null auto_increment,
nome_setor varchar(100)
);

create table if not exists Categorias(
id_categoria int primary key not null auto_increment,
nome_categoria varchar(100) not null,
id_setor int not null,
foreign key(id_setor) references Setores(id_setor),
desconto_categoria double
);

create table if not exists Produtos(
id_produto int primary key not null auto_increment,
nome_produto varchar(100),
preco_produto double not null,
unidades_produto int not null,
id_categoria int not null,
foreign key(id_categoria) references Categorias(id_categoria)
);

CREATE TABLE `loja`.`usuarios` (
  `email` VARCHAR(69) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`email`));
