<?php
// Inclui as classes Banco, Categoria e Produto, que provavelmente contêm funcionalidades relacionadas ao banco de dados, Categorias e produtos
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");
require_once ("modelo/Produto.php");

// Obtém o conteúdo do corpo da requisição HTTP
$textoRecebido = file_get_contents("php://input");
// Decodifica o JSON recebido em um objeto PHP, ou interrompe o script caso o formato seja incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

// Cria um novo objeto da classe Categoria
$Produto = new Produto();

// Define o ID do Categoria do produto com base no JSON recebido
$Produto->setid_produto($id_produto);

// Cria um novo objeto da classe Produto
$objetoProduto = new Produto();

// Define o ID do produto com base na variável $idProduto (presumivelmente definida anteriormente)
$objetoProduto->setid_produto($id_produto);
// Define o nome do produto com base no JSON recebido
$objetoProduto->setnome_Produto($objJson->Produto->nome_produto);
// Define o preco_produto do produto com base no JSON recebido
$objetoProduto->setpreco_produto($objJson->Produto->preco_produto);
// Define a unidades_produto do produto com base no JSON recebido
$objetoProduto->setunidades_produto($objJson->Produto->unidades_produto);
// Define se o produto recebe vale transporte com base no JSON recebido

// Define o Categoria do produto com base no objeto Categoria criado anteriormente
$objetoProduto->setIdCategoria($objJson->Produto->id_categoria);

// Verifica se o nome do produto está vazio
if ($objetoProduto->getnome_Produto() == "") {
    // Define os atributos da resposta indicando erro
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
}
// Verifica se o nome do produto possui menos de 3 caracteres
else if (strlen($objetoProduto->getnome_Produto()) < 2) {
    // Define os atributos da resposta indicando erro
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 2 caracteres";
} else {
    // Tenta atualizar os dados do produto no banco de dados
    if ($objetoProduto->update() == true) {
        // Define os atributos da resposta indicando sucesso
        $objResposta->cod = 3;
        $objResposta->status = true;
        $objResposta->msg = "Atualizado com sucesso";
        // Inclui os dados do Categoria atualizado na resposta
        $objResposta->CategoriaAtualizado = $Produto;
    } else {
        // Define os atributos da resposta indicando erro
        $objResposta->cod = 4;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao atualizar produto";
    }
}
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>