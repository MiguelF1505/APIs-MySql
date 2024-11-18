<?php
// Inclui as classes Banco, Categoria e Produto, que contêm funcionalidades relacionadas ao banco de dados, Categorias e produtos
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");
require_once ("modelo/Produto.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

// Cria um novo objeto da classe Produto
$Produto = new Produto();

// Define o ID do produto com base na variável $idProduto (presumivelmente definida anteriormente)
$Produto->setid_produto($id_produto);

// Chama o método readByID() para recuperar os dados do produto com o ID específico
$vetor = $Produto->readById();

// Define os atributos do objeto resposta para indicar que a operação foi executada com sucesso e inclui o vetor de produtos
$objResposta->cod = 1;
$objResposta->status = true;
$objResposta->msg = "executado com sucesso";
$objResposta->Categorias = $vetor;

// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
