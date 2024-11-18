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
// Define o ID do Setor a ser atualizado
$objSetor->setid_setor($objJson->Setor->id_setor);
// Define o nome do Setor com base nos dados recebidos do JSON
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
// Se todas as condições anteriores forem atendidas, tenta atualizar o Setor
else {
    // Verifica se a atualização do Setor foi bem-sucedida
    if ($objSetor->update() == true) {
        $objResposta->cod = 3;
        $objResposta->status = true;
        $objResposta->msg = "Atualizado com sucesso";
        $objResposta->SetorAtualizado = $objSetor;
    } 
    // Se houver erro na atualização do Setor, define a mensagem de erro
    else {
        $objResposta->cod = 4;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Setor";
    }
}
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
