<?php
// Inclui as classes Banco e Categoria, que provavelmente contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");

// Definição da classe Produto, que implementa a interface JsonSerializable
class Produto implements JsonSerializable
{
    // Propriedades privadas da classe
    private $id_produto;
    private $nome_Produto;
    private $preco_produto;
    private $unidades_produto;
    private $Categoria;

    // Construtor da classe
    public function __construct()
    {
        // Inicializa a propriedade $Categoria com um novo objeto da classe Categoria
        $this->Categoria = new Categoria();
    }

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do produto
        $respostaPadrao = new stdClass();
        $respostaPadrao->id_produto = $this->id_produto;
        $respostaPadrao->nome_Produto = $this->nome_Produto;
        $respostaPadrao->preco_produto = $this->preco_produto;
        // A unidades_produto não é incluída na serialização por motivos de segurança
        //$respostaPadrao->unidades_produto = $this->unidades_produto;
        $respostaPadrao->id_Categoria = $this->Categoria->getid_Categoria();
        $respostaPadrao->nome_Categoria = $this->Categoria->getnome_Categoria();
        $respostaPadrao->id_setor = $this->Categoria->getid_Setor();
        return $respostaPadrao;
    }

    // Método para criar um novo produto no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo produto
        $SQL = "insert into Produtos (nome_Produto, preco_produto, unidades_produto, id_categoria) values(?,?,?,?)";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do Categoria associado ao produto
        $id_Categoria = $this->Categoria->getid_Categoria();
        // Define os parâmetros da consulta com os dados do produto e o ID do Categoria
        $prepararSQL->bind_param("sdii", $this->nome_Produto, $this->preco_produto, $this->unidades_produto, $id_Categoria);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o ID do produto cadastrado
        $idCadastrado = $conexao->insert_id;
        // Define o ID do produto na instância atual da classe
        $this->setid_produto($idCadastrado);
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para verificar se um produto já existe no banco de dados
    public function isProduto()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos produtos possuem o mesmo e-mail
        $SQL = "SELECT count(*) qtd FROM Produtos WHERE nome_produto=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o e-mail do produto
        $prepararSQL->bind_param("s", $this->nome_Produto);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Extrai o objeto da tupla
        $tupla = $matrizTuplas->fetch_object();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a quantidade de produtos encontrados é maior que zero
        return $tupla->qtd > 0;
    }

    // Método para atualizar os dados de um produto no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar os dados do produto
        $SQL = "update Produtos set nome_produto=?, preco_produto=?, unidades_produto=?, id_categoria=? where id_produto=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do Categoria associado ao produto
        $id_Categoria = $this->getCategoria()->getid_Categoria();
        // Define os parâmetros da consulta com os novos dados do produto e o ID do Categoria
        $prepararSQL->bind_param("sdiii", $this->nome_Produto, $this->preco_produto, $this->unidades_produto, $id_Categoria, $this->id_produto);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        $read = $this->readById()[0];
        $this->setCategoria($read->getCategoria());
        $this->setnome_Produto($read->getnome_Produto());
        $this->setpreco_produto($read->getpreco_produto());
        $this->setunidades_produto($read->getunidades_produto());
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para excluir um produto do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um produto pelo ID
        $SQL = "delete from Produtos where id_produto = ?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do produto
        $prepararSQL->bind_param("i", $this->id_produto);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para obter os dados de um produto pelo ID
    public function readById()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter os dados de um produto pelo ID
        $SQL = "SELECT * FROM Produtos JOIN Categorias ON Produtos.id_categoria = Categorias.id_categoria WHERE id_produto=?; ";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do produto
        $prepararSQL->bind_param("i", $this->id_produto);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();

        // Inicializa um array para armazenar os produtos encontrados
        $Produto[0] = new Produto();
        $tupla = $matrizTuplas->fetch_object();
        // Itera sobre as tuplas retornadas
        $Produtos[0] = new Produto();
        $Produtos[0]->setid_produto($tupla->id_produto);
        $Produtos[0]->setnome_Produto($tupla->nome_produto);
        $Produtos[0]->setpreco_produto($tupla->preco_produto);
        $Produtos[0]->setunidades_produto($tupla->unidades_produto);

        // Cria um novo objeto da classe Categoria e define seus dados
        $Categoria = new Categoria();
        $Categoria->setid_categoria($tupla->id_categoria);
        $Categoria->setdesconto_categoria($tupla->desconto_categoria);
        $Categoria->setnome_Categoria($tupla->nome_categoria);
        $Categoria->setid_Setor($tupla->id_setor);

        // Define o Categoria do produto
        $Produtos[0]->setCategoria($Categoria);
        
        // Retorna o array contendo os produtos encontrados
        return $Produtos;
    }

    // Método para obter todos os produtos
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter todos os produtos e seus Categorias
        $SQL = "SELECT * FROM Produtos JOIN Categorias ON Produtos.id_categoria = Categorias.id_Categoria order by nome_produto";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Inicializa um contador
        $i = 0;
        // Inicializa um array para armazenar os produtos encontrados
        $Produtos = array();
        // Itera sobre as tuplas retornadas
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria um novo objeto da classe Produto e define seus dados
            $Produtos[$i] = new Produto();
            $Produtos[$i]->setid_produto($tupla->id_produto);
            $Produtos[$i]->setnome_Produto($tupla->nome_produto);
            $Produtos[$i]->setpreco_produto($tupla->preco_produto);
            $Produtos[$i]->setunidades_produto($tupla->unidades_produto);

            // Cria um novo objeto da classe Categoria e define seus dados
            $Categoria = new Categoria();
            $Categoria->setid_categoria($tupla->id_categoria);
            $Categoria->setdesconto_categoria($tupla->desconto_categoria);
            $Categoria->setnome_Categoria($tupla->nome_categoria);
            $Categoria->setid_Setor($tupla->id_setor);

            // Define o Categoria do produto
            $Produtos[$i]->setCategoria($Categoria);
            // Incrementa o contador
            $i++;
        }
        // Retorna o array contendo os produtos encontrados
        return $Produtos;
    }

    // Método getter para id_produto
    public function getid_produto()
    {
        return $this->id_produto;
    }

    // Método setter para id_produto
    public function setid_produto($id_produto)
    {
        $this->id_produto = $id_produto;
        return $this;
    }

    // Método getter para nome_Produto
    public function getnome_Produto()
    {
        return $this->nome_Produto;
    }

    // Método setter para nome_Produto
    public function setnome_Produto($nome_Produto)
    {
        $this->nome_Produto = $nome_Produto;
        return $this;
    }

    // Método getter para preco_produto
    public function getpreco_produto()
    {
        return $this->preco_produto;
    }

    // Método setter para preco_produto
    public function setpreco_produto($preco_produto)
    {
        $this->preco_produto = $preco_produto;
        return $this;
    }

    // Método getter para unidades_produto
    public function getunidades_produto()
    {
        return $this->unidades_produto;
    }

    // Método setter para unidades_produto
    public function setunidades_produto($unidades_produto)
    {
        $this->unidades_produto = $unidades_produto;
        return $this;
    }

    // Método getter para Categoria
    public function getCategoria()
    {
        return $this->Categoria;
    }

    public function setCategoria($Categoria)
    {
        $this->Categoria = $Categoria;
        return $this;
    }

    // Método setter para Categoria
    public function setIdCategoria($Categoria)
    {
        $this->Categoria->setid_categoria($Categoria);
        return $this;
    }
}
?>