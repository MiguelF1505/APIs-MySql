<?php
// Inclui as classes Categoria contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Categoria.php");

// Obtém o nome temporário do arquivo CSV enviado pelo formulário HTML
$nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];

// Abre o arquivo CSV no modo de leitura
$ponteiroArquivo = fopen($nomeArquivo, "r");

// Loop que lê cada linha do arquivo CSV
$qtdCategorias = 0;
$objCategoria = array();
while (($linhaArguivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
    // Converte os valores da linha para UTF-8, caso necessário
    $linhaArguivo = array_map("utf8_encode", $linhaArguivo);

    // Cria um novo objeto da classe Categoria
    $objCategoria[$qtdCategorias] = new Categoria();

    // Define o nome do Categoria recebido da coluna zero do arquivo csv
    $objCategoria[$qtdCategorias]->setnome_Categoria($linhaArguivo[0]);

    // Chama o método para criar o Categoria no banco de dados
    if ($objCategoria[$qtdCategorias]->create() == true) {
        $qtdCategorias++;
    }
}
$resposta = new stdClass();
$resposta->status = true;
$resposta->msg = "Categorias cadastrados com sucesso";
$resposta->cadastrados = $objCategoria;
$resposta->totalCategorias = $qtdCategorias;
echo json_encode($resposta);

?>