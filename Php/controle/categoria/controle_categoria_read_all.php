<?php
// Inclui as classes Banco e Categoria, que contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Categoria
$objCategoria = new Categoria();

// Obtém todos os Categorias do banco de dados
//escolha entre um dos métodos
$vetor = $objCategoria ->readAll(); // vetor de objetos do tipo Categoria
//$vetor = $objCategoria ->readAllToMatrizArrayAssociativo(); //Vetor associativo com os campos da tabela Categoria

// Define o código de resposta como 1
$objResposta->cod = 1;
// Define o status da resposta como verdadeiro
$objResposta->status = true;
// Define a mensagem de sucesso
$objResposta->msg = "executado com sucesso";
// Define o vetor de Categorias na resposta
$objResposta->Categorias = $vetor;

// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
