<?php
// Inclui as classes Banco e Setor, que contêm funcionalidades relacionadas ao banco de dados e aos setores
require_once ("modelo/Banco.php");
require_once ("modelo/Setor.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Setor
$objSetor = new Setor();

// Obtém todos os setores do banco de dados
//escolha entre um dos métodos
$vetor = $objSetor ->readAll(); // vetor de objetos do tipo Setor
//$vetor = $objSetor ->readAllToMatrizArrayAssociativo(); //Vetor associativo com os campos da tabela Setor

// Define o código de resposta como 1
$objResposta->cod = 1;
// Define o status da resposta como verdadeiro
$objResposta->status = true;
// Define a mensagem de sucesso
$objResposta->msg = "executado com sucesso";
// Define o vetor de setores na resposta
$objResposta->setores = $vetor;

// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
