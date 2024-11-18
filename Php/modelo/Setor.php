<?php
// Inclui o arquivo Banco.php, que contém funcionalidades relacionadas ao banco de dados
require_once ("modelo/Banco.php");

// Definição da classe Setor, que implementa a interface JsonSerializable
class Setor implements JsonSerializable
{
    // Propriedades privadas da classe
    private $id_setor;
    private $nome_setor;

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do Setor
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_setor = $this->id_setor;
        $objetoResposta->nome_setor = $this->nome_setor;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    // Método para criar um novo Setor no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo Setor
        $SQL = "INSERT INTO Setores (nome_setor)VALUES(?);";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Setor
        $prepareSQL->bind_param("s", $this->nome_setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o ID do Setor inserido
        $idCadastrado = $conexao->insert_id;
        // Define o ID do Setor na instância atual da classe
        $this->setid_setor($idCadastrado);
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para excluir um Setor do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um Setor pelo ID
        $SQL = "delete from Setores where id_setor=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Setor
        $prepareSQL->bind_param("i", $this->id_setor);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de um Setor no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar o nome do Setor pelo ID
        $SQL = "update Setores set nome_setor = ? where id_setor=?";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define os parâmetros da consulta com o novo nome do Setor e o ID do Setor
        $prepareSQL->bind_param("si", $this->nome_setor, $this->id_setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para verificar se um Setor já existe no banco de dados
    public function isSetor()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos Setores possuem o mesmo nome
        $SQL = "SELECT COUNT(*) AS qtd FROM Setores WHERE nome_setor = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Setor
        $prepareSQL->bind_param("s", $this->nome_setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantidade de Setores encontrados é maior que zero
        return $objTupla->qtd > 0;

    }

    // Método para ler todos os Setores da tabela Setor
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Setores ordenados por nome
        $SQL = "Select * from Setores
        ";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Setores
        $vetorSetores = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Setor para cada tupla encontrada
            $vetorSetores[$i] = new Setor();
            // Define o ID e o nome do Setor na instância
            $vetorSetores[$i]->setid_setor($tupla->id_setor);
            $vetorSetores[$i]->setnome_setor($tupla->nome_setor);
            $i++;
        }
        // Retorna o vetor com os Setores encontrados
        return $vetorSetores;
    }

    public function readAllToMatrizArrayAssociativo()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Setores ordenados por nome
        $SQL = "SELECT * FROM Setores ORDER BY nome_setor";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Obtém o resultado da consulta como um array multidimensional
        $dados = $matrizTuplas->fetch_all(MYSQLI_ASSOC);
        // Retorna o JSON
        return $dados;
    }
    // Método para ler um Setor do banco de dados com base no ID
    public function readByID()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Setor pelo ID
        $SQL = "SELECT * FROM Setores WHERE id_setor=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Setor
        $prepareSQL->bind_param("i", $this->id_setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Setores
        $vetorSetores = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Setor para cada tupla encontrada
            $vetorSetores[$i] = new Setor();
            // Define o ID e o nome do Setor na instância
            $vetorSetores[$i]->setid_setor($tupla->id_setor);
            $vetorSetores[$i]->setnome_setor($tupla->nome_setor);
            $i++;
        }
        // Retorna o vetor com os Setores encontrados
        return $vetorSetores;
    }

    public function readByName()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Setor pelo ID
        $SQL = "SELECT * FROM Setores WHERE nome_setor=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Setor
        $prepareSQL->bind_param("s", $this->nome_setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Setores
        $vetorSetores = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Setor para cada tupla encontrada
            $vetorSetores[$i] = new Setor();
            // Define o ID e o nome do Setor na instância
            $vetorSetores[$i]->setid_setor($tupla->id_setor);
            $vetorSetores[$i]->setnome_setor($tupla->nome_setor);
            $i++;
        }
        // Retorna o vetor com os Setores encontrados
        return $vetorSetores;
    }

    // Método getter para id_setor
    public function getid_setor()
    {
        return $this->id_setor;
    }

    // Método setter para id_setor
    public function setid_setor($id_setor)
    {
        $this->id_setor = $id_setor;

        return $this;
    }

    // Método getter para nome_setor
    public function getnome_setor()
    {
        return $this->nome_setor;
    }

    // Método setter para nome_setor
    public function setnome_setor($nameSetor)
    {
        $this->nome_setor = $nameSetor;

        return $this;
    }
}

?>