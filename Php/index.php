<?php

// Inclui o arquivo Router.php, que contém a definição da classe Router
require_once ("modelo/Router.php");

// Instancia um objeto da classe Router
$roteador = new Router();

// Define uma rota para a obtenção de todos os setores
$roteador->get("/setores", function () {
    // Requer o arquivo de controle responsável por obter todos os setores
    require_once ("controle/setor/controle_setor_read_all.php");
});

// Define uma rota para a obtenção de um setor específico pelo ID
$roteador->get("/setores/(\d+)", function ($id_setor) {
    // Requer o arquivo de controle responsável por obter um setor pelo ID
    require_once ("controle/setor/controle_setor_read_by_id.php");
});

// Define uma rota para a criação de um novo setor
$roteador->post("/setores", function () {
    // Requer o arquivo de controle responsável por criar um novo setor
    require_once ("controle/setor/controle_setor_create.php");
});


// Define uma rota para a atualização de um setor existente pelo ID
$roteador->put("/setores/(\d+)", function ($id_setor) {
    // Requer o arquivo de controle responsável por atualizar um setor pelo ID
    require_once ("controle/setor/controle_setor_update.php");
});

// Define uma rota para a exclusão de um setor existente pelo ID
$roteador->delete("/setores/(\d+)", function ($id_setor) {
    // Requer o arquivo de controle responsável por excluir um setor pelo ID
    require_once ("controle/setor/controle_setor_delete.php");
});

// Define uma rota para a obtenção de todos os categorias
$roteador->get("/categorias", function () {
    // Requer o arquivo de controle responsável por obter todos os categorias
    require_once ("controle/categoria/controle_categoria_read_all.php");
});

// Define uma rota para a obtenção de um categoria específico pelo ID
$roteador->get("/categorias/(\d+)", function ($id_categoria) {
    // Requer o arquivo de controle responsável por obter um categoria pelo ID
    require_once ("controle/categoria/controle_categoria_read_by_id.php");
});

// Define uma rota para a criação de um novo categoria
$roteador->post("/categorias", function () {
    // Requer o arquivo de controle responsável por criar um novo categoria
    require_once ("controle/categoria/controle_categoria_create.php");
});

// Define uma rota para a atualização de um categoria existente pelo ID
$roteador->put("/categorias/(\d+)", function ($id_categoria) {
    // Requer o arquivo de controle responsável por atualizar um categoria pelo ID
    require_once ("controle/categoria/controle_categoria_update.php");
});

// Define uma rota para a exclusão de um categoria existente pelo ID
$roteador->delete("/categorias/(\d+)", function ($id_categoria) {
    // Requer o arquivo de controle responsável por excluir um categoria pelo ID
    require_once ("controle/categoria/controle_categoria_delete.php");
});


// Define uma rota para a obtenção de todos os produto
$roteador->get("/produtos", function () {
    // Requer o arquivo de controle responsável por obter todos os produto
    require_once ("controle/produto/controle_produto_read_all.php");
});

// Define uma rota para a obtenção de um produto específico pelo ID
$roteador->get("/produtos/(\d+)", function ($id_produto) {
    // Requer o arquivo de controle responsável por obter um produto pelo ID
    require_once ("controle/produto/controle_produto_read_by_id.php");
});

// Define uma rota para a criação de um novo produto
$roteador->post("/produtos", function () {
    // Requer o arquivo de controle responsável por criar um novo produto
    require_once ("controle/produto/controle_produto_create.php");
});

// Define uma rota para a atualização de um produto existente pelo ID
$roteador->put("/produtos/(\d+)", function ($id_produto) {
    // Requer o arquivo de controle responsável por atualizar um produto pelo ID
    require_once ("controle/produto/controle_produto_update.php");
});

// Define uma rota para a exclusão de um produto existente pelo ID
$roteador->delete("/produtos/(\d+)", function ($id_produto) {
    // Requer o arquivo de controle responsável por excluir um produto pelo ID
    require_once ("controle/produto/controle_produto_delete.php");
});

// Define uma rota para a obtenção de todos os usuarios
$roteador->get("/usuarios", function () {
    // Requer o arquivo de controle responsável por obter todos os usuarios
    require_once ("controle/usuario/controle_usuario_read_all.php");
});

// Define uma rota para a obtenção de um usuario específico pelo ID
$roteador->get("/usuarios/(.*)", function ($idUsuario) {
    // Requer o arquivo de controle responsável por obter um usuario pelo ID
    require_once ("controle/usuario/controle_usuario_read_by_id.php");
});

// Define uma rota para a criação de um novo usuario
$roteador->post("/usuarios", function () {
    // Requer o arquivo de controle responsável por criar um novo usuario
    require_once ("controle/usuario/controle_usuario_create.php");
});

$roteador->post("/login", function () {
    // Requer o arquivo de controle responsável por criar um novo usuario
    require_once ("controle/usuario/controle_usuario_login.php");
});

// Define uma rota para a atualização de um usuario existente pelo ID
$roteador->put("/usuarios/(.*)", function ($idUsuario) {
    // Requer o arquivo de controle responsável por atualizar um usuario pelo ID
    require_once ("controle/usuario/controle_usuario_update.php");
});

// Define uma rota para a exclusão de um usuario existente pelo ID
$roteador->delete("/usuarios/(.*)", function ($idUsuario) {
    // Requer o arquivo de controle responsável por excluir um usuario pelo ID
    require_once ("controle/usuario/controle_usuario_delete.php");
});

// Executa o roteador para lidar com as requisições
$roteador->run();