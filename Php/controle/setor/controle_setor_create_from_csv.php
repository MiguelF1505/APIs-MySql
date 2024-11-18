<?php
// Inclui as classes Setor contêm funcionalidades relacionadas ao banco de dados e aos Setors
require_once ("modelo/Setor.php");

// Obtém o nome temporário do arquivo CSV enviado pelo formulário HTML
$nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];

// Abre o arquivo CSV no modo de leitura
$ponteiroArquivo = fopen($nomeArquivo, "r");

// Loop que lê cada linha do arquivo CSV
$qtdSetors = 0;
$objSetor = [];
while (($linhaArquivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
    // Converte os valores da linha para UTF-8, caso necessário
    $linhaArquivo = array_map("utf8_encode", $linhaArquivo);

    // Cria um novo objeto da classe Setor
    $objSetor[$qtdSetors] = new Setor();

    // Define o nome do Setor recebido da coluna zero do arquivo csv
    $objSetor[$qtdSetors]->setnome_setor($linhaArquivo[0]);

    // Chama o método para criar o Setor no banco de dados
    if ($objSetor[$qtdSetors]->create() == true) {
        $qtdSetors++;
    }
}
$resposta = new stdClass();
$resposta->status = true;
$resposta->msg = "Setores cadastrados com sucesso";
$resposta->cadastrados = $objSetor;
$resposta->totalSetors = $qtdSetors;
echo json_encode($resposta);

?>