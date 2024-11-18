<?php
use Firebase\JWT\MeuTokenJWT;
// Inclui a classe Usuario
require_once ("modelo/Usuario.php");
require_once("modelo/MeuTokenJWT.php");


// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");

// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');


// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Usuario
$Usuario = new Usuario();

// Define os atributos do funcionário com base nos dados recebidos do JSON

$Usuario->setEmail($objJson->Usuario->email);
$Usuario->setSenha($objJson->Usuario->senha);

// Verifica se o email do funcionário está vazio
if ($Usuario->getEmail() == '') {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o email nao pode ser vazio";
} else {
    // Verifica se o cadastro do funcionário foi bem-sucedido
    if ($Usuario->login() == true) {
        $tokenJWT = new MeuTokenJWT();

        $objClaimsToken = new stdClass();
        $objClaimsToken->email = $Usuario->getEmail();
     
        $novoToken =  $tokenJWT->gerarToken($objClaimsToken);
      

        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->msg = "Login Efetuado com sucesso";
        $objResposta->Usuario = $Usuario;
        $objResposta->token = $novoToken;
    } 
    // Se houver erro no cadastro do funcionário, define a mensagem de erro
    else {
        $objResposta->cod = 2;
        $objResposta->status = false;
        $objResposta->msg = "Login invalido";
    }
}

// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");

// Define o código de status da resposta como 201 (Created) se o cadastro foi bem-sucedido, caso contrário, como 200 (OK)
if ($objResposta->status == true) {
    header("HTTP/1.1 200");
} else {
    header("HTTP/1.1 401");
}

// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);

?>
