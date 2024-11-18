<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
// Inclui as classes Banco e Categoria, que contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");

// Obtém os dadops enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Categoria
$objCategoria = new Categoria();

// Define o nome do Categoria recebido do JSON no objeto Categoria
$objCategoria->setnome_Categoria($objJson->Categoria->nome_Categoria);

$objCategoria->setid_Setor($objJson->Categoria->setor_Categoria);

$objCategoria->setdesconto_categoria($objJson->Categoria->desconto_Categoria);


// Verifica se o nome do Categoria está vazio
if ($objCategoria->getnome_Categoria() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
} 
// Verifica se já existe um Categoria cadastrado com o mesmo nome
else if ($objCategoria->isCategoria() == true) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "Ja existe um Categoria cadastrado com o nome: " . $objCategoria->getnome_Categoria();

}else if($objCategoria->getdesconto_categoria() < 0 || $objCategoria->getdesconto_categoria() >= 100) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o desconto nao pode ser menor que zero ou maior ou igual a cem";
}
// Se todas as condições anteriores forem atendidas, tenta criar um novo Categoria
else {
    // Verifica se a criação do novo Categoria foi bem-sucedida
    if ($objCategoria->create() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novoCategoria = $objCategoria;
    } 
    // Se houver erro na criação do Categoria, define a mensagem de erro
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Categoria";
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
