<?php
// Inclui as classes Banco e Categoria, que contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Categoria
$objCategoria = new Categoria();

$objCategoria->setid_categoria($id_categoria);
// Define o ID do Categoria a ser atualizado
$objCategoria->setdesconto_categoria($objJson->Categoria->desconto_categoria);
// Define o nome do Categoria com base nos dados recebidos do JSON
$objCategoria->setnome_Categoria($objJson->Categoria->nome_categoria);
// Define o id do setor da Categoria com base nos dados recebidos do JSON
$objCategoria->setid_Setor($objJson->Categoria->id_setor);


// Verifica se o nome do Categoria está vazio
if ($objCategoria->getnome_Categoria() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
} 
// Verifica se o nome do Categoria tem menos de 3 caracteres
else if (strlen($objCategoria->getnome_Categoria()) < 3) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 3 caracteres";
} 
// Se todas as condições anteriores forem atendidas, tenta atualizar o Categoria
else {
    // Verifica se a atualização do Categoria foi bem-sucedida
    if ($objCategoria->update() == true) {
        $objResposta->cod = 3;
        $objResposta->status = true;
        $objResposta->msg = "Atualizado com sucesso";
        $objResposta->CategoriaAtualizado = $objCategoria;
    } 
    // Se houver erro na atualização do Categoria, define a mensagem de erro
    else {
        $objResposta->cod = 4;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Categoria";
    }
}
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
