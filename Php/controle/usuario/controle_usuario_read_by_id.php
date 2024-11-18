<?php
use Firebase\JWT\MeuTokenJWT;

// Inclui as classes Banco e Usuario, que contêm funcionalidades relacionadas ao banco de dados e aos Usuarios
require_once ("modelo/Banco.php");
require_once ("modelo/Usuario.php");

require_once ("modelo/MeuTokenJWT.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

$headers = getallheaders();
$autorization = $headers['Authorization'];

$meuToken = new MeuTokenJWT();

if ($meuToken->validarToken($autorization) == true) {
    $payloadRecuperado = $meuToken->getPayload();
    // Cria um novo objeto da classe Usuario
    $objUsuario = new Usuario();
    // Define o ID do Usuario a ser lido
    $objUsuario->setEmail($idUsuario);

    // Obtém o Usuario específico do banco de dados com base no ID fornecido
    $vetor = $objUsuario->readByemail();

    // Define o código de resposta como 1
    $objResposta->cod = 1;
    // Define o status da resposta como verdadeiro
    $objResposta->status = true;
    // Define a mensagem de sucesso
    $objResposta->msg = "executado com sucesso";
    // Define o vetor de Usuarios na resposta
    $objResposta->Usuarios = $vetor;

    $objResposta->token = $meuToken->gerarToken($payloadRecuperado);

    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");

} else {
    header("HTTP/1.1 401");
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "Token inválido";
}

// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>