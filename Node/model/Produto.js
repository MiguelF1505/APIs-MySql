// Importa o módulo Banco para realizar conexões com o banco de dados.
const Banco = require('./Banco');

// Define a classe Produto para representar a entidade Produto.
class Produto {
    // Construtor da classe Produto que inicializa as propriedades.
    constructor() {
        this._id_produto = null;  // ID da produto, inicialmente nulo.
        this._nome_produto = null;  // Nome do produto, inicialmente uma string vazia.
        this._preco_produto = null;
        this._unidades_produto = null;
        this._id_categoria = null;
    }

    // Método assíncrono para criar um novo produto no banco de dados.
    async create() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'INSERT INTO produtos (nome_produto, preco_produto, unidades_produto, id_categoria) VALUES (?, ?, ?, ?);';  // Query SQL para inserir o nome do produto.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._nome_produto, this._preco_produto, this._unidades_produto, this._id_categoria]);  // Executa a query.
            this._id_produto = result.insertId;  // Armazena o ID gerado pelo banco de dados.
            return result.affectedRows > 0;  // Retorna true se a inserção afetou alguma linha.
        } catch (error) {
            console.error('Erro ao criar o produto:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para excluir um produto do banco de dados.
    async delete() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'DELETE FROM produtos WHERE id_produto = ?;';  // Query SQL para deletar um produto pelo ID.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._id_produto]);  // Executa a query de exclusão.
            return result.affectedRows > 0;  // Retorna true se alguma linha foi afetada (produto deletado).
        } catch (error) {
            console.error('Erro ao excluir o produto:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para atualizar os dados de um produto no banco de dados.
    async update() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'UPDATE produtos SET nome_produto = ?, preco_produto = ?, unidades_produto = ?, id_categoria = ? WHERE id_produto = ?;';  // Query SQL para atualizar o nome de um produto.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._nome_produto, this._preco_produto, this._unidades_produto, this._id_categoria, this._id_produto]);  // Executa a query de atualização.
            return result.affectedRows > 0;  // Retorna true se a atualização afetou alguma linha.
        } catch (error) {
            console.error('Erro ao atualizar o produto:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para verificar se um produto já existe no banco de dados.
    async isProdutoByNomeProduto() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT COUNT(*) AS qtd FROM produtos WHERE nome_produto = ?;';  // Query SQL para contar produtos com o mesmo nome.

        try {
            const [rows] = await conexao.promise().execute(SQL, [this._nome_produto]);  // Executa a query.
            return rows[0].qtd > 0;  // Retorna true se houver algum produto com o mesmo nome.
        } catch (error) {
            console.error('Erro ao verificar o produto:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    async isProdutoById(id_produto) {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT COUNT(*) AS qtd FROM produtos WHERE id_produto = ?;';  // Query SQL para contar produtos com o mesmo nome.
        try {
            const [rows] = await conexao.promise().execute(SQL, [id_produto]);  // Executa a query.
            return rows[0].qtd > 0;  // Retorna true se houver algum produto com o mesmo nome.
        } catch (error) {
            console.error('Erro ao verificar o produto:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para ler todos os produtos do banco de dados.
    async readAll() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT * FROM produtos ORDER BY nome_produto;';  // Query SQL para selecionar todos os produtos ordenados pelo nome.

        try {
            const [rows] = await conexao.promise().execute(SQL);  // Executa a query de seleção.
            return rows;  // Retorna a lista de produtos.
        } catch (error) {
            console.error('Erro ao ler produtos:', error);  // Exibe erro no console se houver falha.
            return [];  // Retorna uma lista vazia caso ocorra um erro.
        }
    }

    // Método assíncrono para ler um produto pelo seu ID.
    async readByID() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT * FROM produtos WHERE id_produto = ?;';  // Query SQL para selecionar um produto pelo ID.

        try {
            const [rows] = await conexao.promise().execute(SQL, [this._id_produto]);  // Executa a query de seleção.
            return rows;  // Retorna o produto correspondente ao ID.
        } catch (error) {
            console.error('Erro ao ler produto pelo ID:', error);  // Exibe erro no console se houver falha.
            return null;  // Retorna null caso ocorra um erro.
        }
    }

    // Getter para obter o valor de id_produto.
    get id_produto() {
        return this._id_produto;
    }

    // Setter para definir o valor de id_produto.
    set id_produto(id_produto) {
        this._id_produto = id_produto;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de nome_produto.
    get nome_produto() {
        return this._nome_produto;
    }

    // Setter para definir o valor de nome_produto.
    set nome_produto(nome_produto) {
        this._nome_produto = nome_produto;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de id_produto.
    get id_categoria() {
        return this._id_categoria;
    }

    // Setter para definir o valor de id_produto.
    set id_categoria(id_categoria) {
        this._id_categoria = id_categoria;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de id_produto.
    get unidades_produto() {
        return this._unidades_produto;
    }

    // Setter para definir o valor de id_produto.
    set unidades_produto(unidades_produto) {
        this._unidades_produto = unidades_produto;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de preco_produto.
    get preco_produto() {
        return this._preco_produto;
    }

    // Setter para definir o valor de preco_produto.
    set preco_produto(preco_produto) {
        this._preco_produto = preco_produto;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }
}

// Exporta a classe Produto para que possa ser utilizada em outros módulos.
module.exports = Produto;
