<?php
// Inclui as classes Banco e Categoria, que provavelmente contêm funcionalidades relacionadas ao banco de dados e aos Categorias
require_once ("modelo/Banco.php");
require_once ("modelo/Categoria.php");

// Definição da classe Funcionario, que implementa a interface JsonSerializable
class Funcionario implements JsonSerializable
{
    // Propriedades privadas da classe
    private $id_produto;
    private $nome_Produto;
    private $preco_produto;
    private $unidades_produto;
    private $recebeValeTransporte;
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
        // Cria um objeto stdClass para armazenar os dados do funcionário
        $respostaPadrao = new stdClass();
        $respostaPadrao->id_produto = $this->id_produto;
        $respostaPadrao->nome_Produto = $this->nome_Produto;
        $respostaPadrao->preco_produto = $this->preco_produto;
        // A unidades_produto não é incluída na serialização por motivos de segurança
        //$respostaPadrao->unidades_produto = $this->unidades_produto;
        $respostaPadrao->recebeValeTransporte = $this->recebeValeTransporte;
        $respostaPadrao->id_Categoria = $this->Categoria->getid_Categoria();
        $respostaPadrao->nome_Categoria = $this->Categoria->getnome_Categoria();
        return $respostaPadrao;
    }

    // Método para criar um novo funcionário no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo funcionário
        $SQL = "insert into Funcionario (nome_Produto, preco_produto, unidades_produto, recebeValeTransporte,Categoria_id_Categoria) values(?,?,?,?,?)";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do Categoria associado ao funcionário
        $id_Categoria = $this->Categoria->getid_Categoria();
        // Define os parâmetros da consulta com os dados do funcionário e o ID do Categoria
        $prepararSQL->bind_param("sssii", $this->nome_Produto, $this->preco_produto, $this->unidades_produto, $this->recebeValeTransporte, $id_Categoria);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o ID do funcionário cadastrado
        $idCadastrado = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setid_produto($idCadastrado);
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para criar um novo funcionário no banco de dados
    public function createFromCSV()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo funcionário
        $SQL = "INSERT into Funcionario 
        (nome_Produto, preco_produto, unidades_produto, recebeValeTransporte,Categoria_id_Categoria) 
        VALUES(?,?,?,?,(SELECT id_Categoria FROM Categoria WHERE nome_Categoria = ? ))";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do Categoria associado ao funcionário
        $nome_Categoria = $this->Categoria->getnome_Categoria();
        // Define os parâmetros da consulta com os dados do funcionário e o ID do Categoria
        $prepararSQL->bind_param(
            "sssis",
            $this->nome_Produto,
            $this->preco_produto,
            $this->unidades_produto,
            $this->recebeValeTransporte,
            $nome_Categoria
        );
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o ID do funcionário cadastrado
        $idCadastrado = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setid_produto($idCadastrado);
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para verificar se um funcionário já existe no banco de dados
    public function isFuncionario()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos funcionários possuem o mesmo e-mail
        $SQL = "SELECT count(*) qtd FROM funcionario WHERE preco_produto=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o e-mail do funcionário
        $prepararSQL->bind_param("s", $this->preco_produto);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Extrai o objeto da tupla
        $tupla = $matrizTuplas->fetch_object();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a quantidade de funcionários encontrados é maior que zero
        return $tupla->qtd > 0;
    }

    // Método para atualizar os dados de um funcionário no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar os dados do funcionário
        $SQL = "update Funcionario set nome_Produto=?, preco_produto=?, unidades_produto=?,recebeValeTransporte=?, Categoria_id_Categoria=? where id_produto=?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Obtém o ID do Categoria associado ao funcionário
        $id_Categoria = $this->getCategoria()->getid_Categoria();
        // Define os parâmetros da consulta com os novos dados do funcionário e o ID do Categoria
        $prepararSQL->bind_param("sssiii", $this->nome_Produto, $this->preco_produto, $this->unidades_produto, $this->recebeValeTransporte, $id_Categoria, $this->id_produto);
        // Executa a consulta
        $executar = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executar;
    }

    // Método para excluir um funcionário do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um funcionário pelo ID
        $SQL = "delete from Funcionario where id_produto = ?";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do funcionário
        $prepararSQL->bind_param("i", $this->id_produto);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Fecha a consulta
        $prepararSQL->close();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para obter os dados de um funcionário pelo ID
    public function readById()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter os dados de um funcionário pelo ID
        $SQL = "SELECT * FROM funcionario JOIN Categoria ON funcionario.Categoria_id_Categoria= Categoria.id_Categoria WHERE id_produto=?; ";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do funcionário
        $prepararSQL->bind_param("i", $this->id_produto);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Inicializa um contador
        $i = 0;
        // Inicializa um array para armazenar os funcionários encontrados
        $funcionario[0] = new Funcionario();
        // Itera sobre as tuplas retornadas
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Define os dados do funcionário na instância atual da classe
            $funcionario[0]->setid_produto($tupla->id_produto);
            $funcionario[0]->setnome_Produto($tupla->nome_Produto);
            $funcionario[0]->setpreco_produto($tupla->preco_produto);
            $funcionario[0]->setunidades_produto($tupla->unidades_produto);
            $funcionario[0]->setRecebeValeTransporte($tupla->recebeValeTransporte);

            // Cria um novo objeto da classe Categoria e define seus dados
            $Categoria = new Categoria();
            $Categoria->setid_Categoria($tupla->id_Categoria);
            $Categoria->setnome_Categoria($tupla->nome_Categoria);

            // Define o Categoria do funcionário
            $funcionario[0]->setCategoria($Categoria);
        }
        // Retorna o array contendo os funcionários encontrados
        return $funcionario;
    }

    // Método para obter todos os funcionários
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para obter todos os funcionários e seus Categorias
        $SQL = "SELECT * FROM Produtos JOIN Categorias ON Produtos.id_Categoria = Categorias.id_Categoria order by nome_Produto";
        // Prepara a consulta
        $prepararSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepararSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepararSQL->get_result();
        // Inicializa um contador
        $i = 0;
        // Inicializa um array para armazenar os funcionários encontrados
        $funcionarios = array();
        // Itera sobre as tuplas retornadas
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria um novo objeto da classe Funcionario e define seus dados
            $funcionarios[$i] = new Produto();
            $funcionarios[$i]->setid_produto($tupla->id_produto);
            $funcionarios[$i]->setnome_Produto($tupla->nome_Produto);
            $funcionarios[$i]->setpreco_produto($tupla->preco_produto);
            $funcionarios[$i]->setunidades_produto($tupla->unidades_produto);
            $funcionarios[$i]->setRecebeValeTransporte($tupla->recebeValeTransporte);

            // Cria um novo objeto da classe Categoria e define seus dados
            $Categoria = new Categoria();
            $Categoria->setid_Setor($tupla->id_Setor);
            $Categoria->setdesconto_categoria($tupla->desconto_categoria);
            $Categoria->setnome_Categoria($tupla->nome_Categoria);

            // Define o Categoria do funcionário
            $funcionarios[$i]->setCategoria($Categoria);
            // Incrementa o contador
            $i++;
        }
        // Retorna o array contendo os funcionários encontrados
        return $funcionarios;
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

    // Método getter para recebeValeTransporte
    public function getRecebeValeTransporte()
    {
        return $this->recebeValeTransporte;
    }

    // Método setter para recebeValeTransporte
    public function setRecebeValeTransporte($recebeValeTransporte)
    {
        $this->recebeValeTransporte = $recebeValeTransporte;
        return $this;
    }

    // Método getter para Categoria
    public function getCategoria()
    {
        return $this->Categoria;
    }

    // Método setter para Categoria
    public function setCategoria($Categoria)
    {
        $this->Categoria = $Categoria;
        return $this;
    }
}
?>