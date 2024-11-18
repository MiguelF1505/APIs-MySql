<?php
// Inclui o arquivo Banco.php, que contém funcionalidades relacionadas ao banco de dados
require_once ("modelo/Banco.php");
require_once ("modelo/Setor.php");

// Definição da classe Categoria, que implementa a interface JsonSerializable
class Categoria implements JsonSerializable
{
    // Propriedades privadas da classe
    private $id_Categoria;
    private $nome_Categoria;
    private $desconto_categoria;

    private $Setor;

    public function __construct()
    {
        // Inicializa a propriedade $Categoria com um novo objeto da classe Categoria
        $this->Setor = new Setor();
    }

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do Categoria
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_Categoria = $this->id_Categoria;
        $objetoResposta->nome_Categoria = $this->nome_Categoria;
        $objetoResposta->desconto_Categoria = $this->desconto_categoria;

        $objetoResposta->id_Setor = $this->Setor->getid_Setor();
        $objetoResposta->nome_Setor = $this->Setor->getnome_setor();

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    // Método para criar um novo Categoria no banco de dados
    public function create()
    {
        if(is_nan($this->Setor->getid_Setor()) != 1){
            echo var_dump($this->Setor->readByName()[0]);
            $this->Setor->setid_Setor($this->Setor->readByName()[0]->getid_Setor());
        }
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo Categoria
        $SQL = "INSERT INTO Categorias (nome_categoria, desconto_categoria, id_setor)VALUES(?, ?, ?);";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Categoria
        $idSet = $this->Setor->getid_setor();
        $prepareSQL->bind_param("sdi", $this->nome_Categoria, $this->desconto_categoria, $idSet);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o ID do Categoria inserido
        $idCadastrado = $conexao->insert_id;
        // Define o ID do Categoria na instância atual da classe
        $this->setid_categoria($idCadastrado);

        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para excluir um Categoria do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um Categoria pelo ID
        $SQL = "delete from Categorias where id_categoria=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Categoria
        $prepareSQL->bind_param("i", $this->id_Categoria);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de um Categoria no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar o nome do Categoria pelo ID
        $SQL = "update Categorias set id_setor = ?, desconto_categoria = ? where id_categoria=?";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        $id_set = $this->Setor->getid_Setor();
        // Define os parâmetros da consulta com o novo nome do Categoria e o ID do Categoria
        $prepareSQL->bind_param("idi", $id_set, $this->desconto_categoria, $this->id_Categoria);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para verificar se um Categoria já existe no banco de dados
    public function isCategoria()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos Categorias possuem o mesmo nome
        $SQL = "SELECT COUNT(*) AS qtd FROM Categorias WHERE nome_categoria = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Categoria
        $prepareSQL->bind_param("s", $this->nome_Categoria);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantidade de Categorias encontrados é maior que zero
        return $objTupla->qtd > 0;

    }

    // Método para ler todos os Categorias da tabela Categoria
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Categorias ordenados por nome
        $SQL = "SELECT * FROM Categorias JOIN Setores ON Categorias.id_setor = Setores.id_setor order by nome_setor";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Categorias
        $vetorCategorias = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Categoria para cada tupla encontrada
            $vetorCategorias[$i] = new Categoria();
            // Define o ID e o nome do Categoria na instância
            $vetorCategorias[$i]->setid_categoria($tupla->id_categoria);
            $vetorCategorias[$i]->setid_Setor($tupla->id_setor);
            $vetorCategorias[$i]->setnome_Categoria($tupla->nome_categoria);
            $vetorCategorias[$i]->setdesconto_categoria($tupla->desconto_categoria);

            $Setor = new Setor();

            $Setor->setid_setor($tupla->id_setor);
            $Setor->setnome_setor($tupla->nome_setor);

            $vetorCategorias[$i]->setor = $Setor;

            $i++;
        }
        // Retorna o vetor com os Categorias encontrados
        return $vetorCategorias;
    }

    public function readAllToMatrizArrayAssociativo()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Categorias ordenados por nome
        $SQL = "SELECT * FROM Categorias ORDER BY nome_Categoria";
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
    // Método para ler um Categoria do banco de dados com base no ID
    public function readByID()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Categoria pelo ID
        $SQL = "SELECT * FROM Categorias WHERE id_categoria=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Categoria
        $prepareSQL->bind_param("i", $this->id_Categoria);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Categorias
        $vetorCategorias = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Categoria para cada tupla encontrada
            $vetorCategorias[$i] = new Categoria();
            // Define o ID e o nome do Categoria na instância
            $vetorCategorias[$i]->setid_categoria($tupla->id_categoria);
            $vetorCategorias[$i]->setid_Setor($tupla->id_setor);
            $vetorCategorias[$i]->setnome_Categoria($tupla->nome_categoria);
            $vetorCategorias[$i]->setdesconto_categoria($tupla->desconto_categoria);

            $Setor = new Setor();

            $Setor->setid_setor($tupla->id_setor);
            $Setor->setnome_setor($tupla->nome_setor);

            $vetorCategorias[$i]->setor = $Setor;
            $i++;
        }
        // Retorna o vetor com os Categorias encontrados
        return $vetorCategorias;
    }

    // Método getter para id_Setor
    public function getid_Setor()
    {
        return $this->Setor->getid_Setor();
    }

    // Método setter para id_Setor
    public function setid_Setor($id_Setor)
    {
        $this->Setor->setid_Setor($id_Setor);

        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Categoria pelo ID
        $SQL = "SELECT nome_setor FROM Setores WHERE id_setor=?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Categoria
        $prepareSQL->bind_param("i", $id_Setor);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        $this->Setor->setnome_setor($prepareSQL->get_result()->fetch_object()->nome_setor);

        return $this;
    }

    // Método getter para nome_Categoria
    public function getnome_Categoria()
    {
        return $this->nome_Categoria;
    }

    // Método setter para nome_Categoria
    public function setnome_Categoria($name_Categoria)
    {
        $this->nome_Categoria = $name_Categoria;

        return $this;
    }

    // Método getter para desconto_categoria
    public function getdesconto_categoria()
    {
        return $this->desconto_categoria;
    }

    // Método setter para desconto_categoria
    public function setdesconto_categoria($descont_categoria)
    {
        $this->desconto_categoria = $descont_categoria;

        return $this;
    }

    // Método getter para desconto_categoria
    public function getid_categoria()
    {
        return $this->id_Categoria;
    }

    // Método setter para desconto_categoria
    public function setid_categoria($id_cat)
    {
        $this->id_Categoria = $id_cat;

        return $this;
    }
}

?>