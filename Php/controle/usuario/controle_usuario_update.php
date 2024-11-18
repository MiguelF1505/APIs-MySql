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
// Define o ID do Usuario a ser atualizado
$objUsuario->setEmail($idUsuario);
// Define o senha do Usuario com base nos dados recebidos do JSON
$objUsuario->setSenha($objJson->Usuario->senha);

// Verifica se a senha do Usuario está vazio
if ($objUsuario->getSenha() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o senha nao pode ser vazio";
} 
// Verifica se a senha do Usuario tem menos de 6 caracteres
else if (strlen($objUsuario->getSenha()) < 6) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "a senha nao pode ser menor do que 6 caracteres";
} 
else {
    // Verifica se a atualização do Usuario foi bem-sucedida
    if ($objUsuario->update() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "Atualizado com sucesso";
        $objResposta->UsuarioAtualizado = $objUsuario;
    } 
    // Se houver erro na atualização do Usuario, define a mensagem de erro
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar novo Usuario";
    }
}
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
