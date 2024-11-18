<?php
// Inclui o arquivo Banco.php, que contém funcionalemailades relacionadas ao banco de dados
require_once ("modelo/Banco.php");

// Definição da classe Usuario, que implementa a interface JsonSerializable
class Usuario implements JsonSerializable
{
    // Propriedades privadas da classe
    private $email;
    private $senha;

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do Usuario
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->email = $this->email;
        $objetoResposta->senha = $this->senha;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    public function login()
    {
        $conexao = Banco::getConexao();
        $senhaComMd5 = MD5($this->senha);
        $SQL = "SELECT COUNT(*) AS qtd, email, senha FROM Usuarios WHERE email=? AND senha=?;";

        $prepararSQL = $conexao->prepare($SQL);

        $prepararSQL->bind_param("ss", $this->email, $senhaComMd5);

        $executar = $prepararSQL->execute();
        $matrizTuplas = $prepararSQL->get_result();
        while($tupla = $matrizTuplas->fetch_object()){
            if($tupla->qtd==1){
                $this->setEmail($tupla->email);
                $this->setSenha($tupla->senha);
                return true;
            }
            
        }
        return false;
    }

    // Método para criar um novo Usuario no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo Usuario
        $SQL = "INSERT INTO Usuarios (email, senha)VALUES(?, ?);";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o senha do Usuario
        $prepareSQL->bind_param("ss", $this->email, md5($this->senha));
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para excluir um Usuario do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um Usuario pelo email
        $SQL = "delete from Usuarios where email = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o email do Usuario
        echo ">>".$this->email;
        $prepareSQL->bind_param("s", $this->email);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de um Usuario no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar o senha do Usuario pelo email
        $SQL = "update Usuarios set senha = ? where email=?";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define os parâmetros da consulta com o novo senha do Usuario e o email do Usuario
        $prepareSQL->bind_param("ss", md5($this->senha), $this->email);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para verificar se um Usuario já existe no banco de dados
    public function isUsuario()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos Usuarios possuem o mesmo senha
        $SQL = "SELECT COUNT(*) AS qtd FROM Usuarios WHERE email = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o senha do Usuario
        $prepareSQL->bind_param("s", $this->email);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantemailade de Usuarios encontrados é maior que zero
        return $objTupla->qtd > 0;

    }

    // Método para ler todos os Usuarios da tabela Usuario
    public function  readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Usuarios ordenados por senha
        $SQL = "Select * from Usuarios";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Usuarios
        $vetorUsuarios = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Usuario para cada tupla encontrada
            $vetorUsuarios[$i] = new Usuario();
            // Define o email e o senha do Usuario na instância
            $vetorUsuarios[$i]->setEmail($tupla->email);
            $vetorUsuarios[$i]->setSenha($tupla->senha);
            $i++;
        }
        // Retorna o vetor com os Usuarios encontrados
        return $vetorUsuarios;
    }

    public function readAllToMatrizArrayAssociativo()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Usuarios ordenados por senha
        $SQL = "SELECT * FROM Usuarios";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Obtém o resultado da consulta como um array multemailimensional
        $dados = $matrizTuplas->fetch_all(MYSQLI_ASSOC);
        // Retorna o JSON
        return $dados;
    }
    // Método para ler um Usuario do banco de dados com base no email
    public function readByemail()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Usuario pelo email
        $SQL = "SELECT * FROM Usuarios WHERE email='?';";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o email do Usuario
        $prepareSQL->bind_param("s", $this->email);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Usuarios
        $vetorUsuarios = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Usuario para cada tupla encontrada
            $vetorUsuarios[$i] = new Usuario();
            // Define o email e o senha do Usuario na instância
            $vetorUsuarios[$i]->setEmail($tupla->email);
            $vetorUsuarios[$i]->setSenha($tupla->senha);
            $i++;
        }
        // Retorna o vetor com os Usuarios encontrados
        return $vetorUsuarios;
    }

    // Método getter para email
    public function getEmail()
    {
        return $this->email;
    }

    // Método setter para email
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    // Método getter para senha
    public function getSenha()
    {
        return $this->senha;
    }

    // Método setter para senha
    public function setSenha($nameUsuario)
    {
        $this->senha = $nameUsuario;

        return $this;
    }
}

?>