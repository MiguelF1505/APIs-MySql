<?php
// Inclui as classes Banco, Produto e Categoria, que contêm funcionalidades relacionadas ao banco de dados, produtos e Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Produto.php");
require_once ("modelo/Categoria.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Produto
$Produto = new Produto();

// Define os atributos do produto com base nos dados recebidos do JSON
$Produto->setnome_Produto($objJson->Produto->nome_produto);
$Produto->setpreco_produto($objJson->Produto->preco_produto);
$Produto->setunidades_produto($objJson->Produto->unidades_produto);
// Define o ID do Categoria do produto com base nos dados recebidos do JSON
$Produto->getCategoria()->setid_categoria($objJson->Produto->id_categoria);

// Verifica se o nome do produto está vazio
if ($Produto->getnome_Produto() == '') {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser vazio";
} 
// Verifica se o nome do produto tem menos de 2 caracteres
elseif (strlen($Produto->getnome_Produto()) < 2) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o nome nao pode ser menor do que 2 caracteres";
} 
// Verifica se o preco_produto do produto está vazio
elseif ($Produto->getpreco_produto() <= 0) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o preco_produto nao pode ser menor ou igual a zero";
} 
// Verifica se já existe um produto cadastrado com o mesmo preco_produto
elseif ($Produto->isProduto() == true) {
    $objResposta->cod = 4;
    $objResposta->status = false;
    $objResposta->msg = "ja existe produto cadastrado com o nome indicado";
}
// Se todas as condições anteriores forem atendidas, tenta cadastrar o novo produto
else {
    // Verifica se o cadastro do produto foi bem-sucedido
    if ($Produto->create() == true) {
        $objResposta->cod = 5;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novoProduto = $Produto;
    } 
    // Se houver erro no cadastro do produto, define a mensagem de erro
    else {
        $objResposta->cod = 6;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar Produto";
    }
}

// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");

// Define o código de status da resposta como 201 (Created) se o cadastro foi bem-sucedido, caso contrário, como 200 (OK)
if ($objResposta->status == true) {
    header("HTTP/1.1 201");
} else {
    header("HTTP/1.1 200");
}

// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
