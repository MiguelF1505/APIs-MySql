<?php
// Inclui a classe Categoria.php, que provavelmente contém funcionalidades relacionadas aos Categorias
require_once ("modelo/Categoria.php");
// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Categoria
$objCategoria = new Categoria();
// Define o ID do Categoria a ser excluído
$objCategoria->setid_categoria($id_categoria);
// Verifica se a exclusão do Categoria foi bem-sucedida
if($objCategoria->delete()==true){
    // Define o código de status da resposta como 204 (No Content)
    header("HTTP/1.1 204");
}else{
    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
       // Define o status da resposta como falso
    $objResposta->status = false;
    // Define o código de resposta como 1
    $objResposta->cod = 1;
    // Define a mensagem de erro
    $objResposta->msg = "Erro ao excluir Categoria";
    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);
}
?>
