<?php
// Inclui as classes Banco e Setor, que contêm funcionalidades relacionadas ao banco de dados e aos setores
require_once ("modelo/Banco.php");
require_once ("modelo/Setor.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Setor
$objSetor = new Setor();

// Define o nome do Setor recebido do JSON no objeto Setor
$objSetor->setnome_setor($objJson->Setor->nome_setor);

// Verifica se o nome do Setor está vazio
if ($objSetor->getnome_setor() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
} 
// Verifica se o nome do Setor tem menos de 3 caracteres
else if (strlen($objSetor->getnome_setor()) < 3) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 3 caracteres";
} 
// Verifica se já existe um Setor cadastrado com o mesmo nome
else if ($objSetor->isSetor() == true) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "Ja existe um Setor cadastrado com o nome: " . $objSetor->getnome_setor();
} 
// Se todas as condições anteriores forem atendidas, tenta criar um novo Setor
else {
    // Verifica se a criação do novo Setor foi bem-sucedida
    if ($objSetor->create() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novoSetor = $objSetor;
    } 
    // Se houver erro na criação do Setor, define a mensagem de erro
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Setor";
    }
}

// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");

// Define o código de status da resposta com base no status da operação
if ($objResposta->status == true) {
    header("HTTP/1.1 201");
} else {
    header("HTTP/1.1 200");
}

// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
