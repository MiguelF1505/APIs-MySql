<?php
// Inclui a classe Produto, que  contém funcionalidades relacionadas a produtos
require_once ("modelo/Produto.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Produto
$Produto = new Produto();
// Define o ID do produto com base na variável $idProduto (presumivelmente definida anteriormente)

$Produto->setid_produto($id_produto);

// Verifica se a exclusão do produto foi bem-sucedida
if ($Produto->delete() == true) {
    // Define o código de status da resposta como 204 (No Content)
    header("HTTP/1.1 204");
} else {
    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
    // Define os atributos do objeto resposta para indicar que ocorreu um erro na exclusão do produto
    $objResposta->status = false;
    $objResposta->cod = 1;
    $objResposta->msg = "Erro ao excluir Produto";
    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);
}
?>
