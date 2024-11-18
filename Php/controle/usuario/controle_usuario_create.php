<?php
// Inclui as classes Banco e Usuario, que contêm funcionalidades relacionadas ao banco de dados e aos Usuarios
require_once ("modelo/Banco.php");
require_once ("modelo/Usuario.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Usuario
$objUsuario = new Usuario();

// Define o email do Usuario recebido do JSON no objeto Usuario
$objUsuario->setEmail($objJson->Usuario->email);
$objUsuario->setSenha($objJson->Usuario->senha);

// Verifica se o email do Usuario está vazio
if ($objUsuario->getEmail() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o email nao pode ser vazio";
} 
// Verifica se o email do Usuario tem menos de 3 caracteres
else if (strlen($objUsuario->getEmail()) < 6) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "o email nao pode ser menor do que 6 caracteres";
}
// Verifica se a senha do Usuario tem menos de 6 caracteres
else if (strlen($objUsuario->getSenha()) < 6) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "a senha nao pode ser menor do que 6 caracteres";
} 
// Verifica se já existe um Usuario cadastrado com o mesmo email
else if ($objUsuario->isUsuario() == true) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "Ja existe um Usuario cadastrado com o email: " . $objUsuario->getEmail();
} 
// Se todas as condições anteriores forem atendidas, tenta criar um novo Usuario
else {
    // Verifica se a criação do novo Usuario foi bem-sucedida
    if ($objUsuario->create() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novoUsuario = $objUsuario;
    } 
    // Se houver erro na criação do Usuario, define a mensagem de erro
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Usuario";
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
