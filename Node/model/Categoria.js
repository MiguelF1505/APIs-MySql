// Importa o módulo Banco para realizar conexões com o banco de dados.
const Banco = require('./Banco');

// Define a classe Categoria para representar a entidade Categoria.
class Categoria {
    // Construtor da classe Categoria que inicializa as propriedades.
    constructor() {
        this._id_categoria = null;  // ID da categoria, inicialmente nulo.
        this._nome_categoria = null;  // Nome do categoria, inicialmente uma string vazia.
        this._desconto_categoria = null;
        this._id_setor = null;
    }

    // Método assíncrono para criar um novo categoria no banco de dados.
    async create() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'INSERT INTO categorias (nome_categoria, desconto_categoria, id_setor) VALUES (?, ?, ?);';  // Query SQL para inserir o nome do categoria.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._nome_categoria, this._desconto_categoria, this._id_setor]);  // Executa a query.
            this._id_categoria = result.insertId;  // Armazena o ID gerado pelo banco de dados.
            return result.affectedRows > 0;  // Retorna true se a inserção afetou alguma linha.
        } catch (error) {
            console.error('Erro ao criar o categoria:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para excluir um categoria do banco de dados.
    async delete() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'DELETE FROM categorias WHERE id_categoria = ?;';  // Query SQL para deletar um categoria pelo ID.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._id_categoria]);  // Executa a query de exclusão.
            return result.affectedRows > 0;  // Retorna true se alguma linha foi afetada (categoria deletado).
        } catch (error) {
            console.error('Erro ao excluir o categoria:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para atualizar os dados de um categoria no banco de dados.
    async update() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'UPDATE categorias SET nome_categoria = ?, desconto_categoria = ?, id_setor = ? WHERE id_categoria = ?;';  // Query SQL para atualizar o nome de um categoria.

        try {
            const [result] = await conexao.promise().execute(SQL, [this._nome_categoria, this._desconto_categoria, this._id_setor, this._id_categoria]);  // Executa a query de atualização.
            return result.affectedRows > 0;  // Retorna true se a atualização afetou alguma linha.
        } catch (error) {
            console.error('Erro ao atualizar o categoria:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para verificar se um categoria já existe no banco de dados.
    async isCategoriaByNomeCategoria() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT COUNT(*) AS qtd FROM categorias WHERE nome_categoria = ?;';  // Query SQL para contar categorias com o mesmo nome.

        try {
            const [rows] = await conexao.promise().execute(SQL, [this._nome_categoria]);  // Executa a query.
            return rows[0].qtd > 0;  // Retorna true se houver algum categoria com o mesmo nome.
        } catch (error) {
            console.error('Erro ao verificar o categoria:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    async isCategoriaById(id_categoria) {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT COUNT(*) AS qtd FROM categorias WHERE id_categoria = ?;';  // Query SQL para contar categorias com o mesmo nome.
        try {
            const [rows] = await conexao.promise().execute(SQL, [id_categoria]);  // Executa a query.
            return rows[0].qtd > 0;  // Retorna true se houver algum categoria com o mesmo nome.
        } catch (error) {
            console.error('Erro ao verificar o categoria:', error);  // Exibe erro no console se houver falha.
            return false;  // Retorna false caso ocorra um erro.
        }
    }

    // Método assíncrono para ler todos os categorias do banco de dados.
    async readAll() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT * FROM categorias ORDER BY nome_categoria;';  // Query SQL para selecionar todos os categorias ordenados pelo nome.

        try {
            const [rows] = await conexao.promise().execute(SQL);  // Executa a query de seleção.
            return rows;  // Retorna a lista de categorias.
        } catch (error) {
            console.error('Erro ao ler categorias:', error);  // Exibe erro no console se houver falha.
            return [];  // Retorna uma lista vazia caso ocorra um erro.
        }
    }

    // Método assíncrono para ler um categoria pelo seu ID.
    async readByID() {
        const conexao = Banco.getConexao();  // Obtém a conexão com o banco de dados.
        const SQL = 'SELECT * FROM categorias WHERE id_categoria = ?;';  // Query SQL para selecionar um categoria pelo ID.

        try {
            const [rows] = await conexao.promise().execute(SQL, [this._id_categoria]);  // Executa a query de seleção.
            return rows;  // Retorna o categoria correspondente ao ID.
        } catch (error) {
            console.error('Erro ao ler categoria pelo ID:', error);  // Exibe erro no console se houver falha.
            return null;  // Retorna null caso ocorra um erro.
        }
    }

    // Getter para obter o valor de id_categoria.
    get id_categoria() {
        return this._id_categoria;
    }

    // Setter para definir o valor de id_categoria.
    set id_categoria(id_categoria) {
        this._id_categoria = id_categoria;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de nome_categoria.
    get nome_categoria() {
        return this._nome_categoria;
    }

    // Setter para definir o valor de nome_categoria.
    set nome_categoria(nome_categoria) {
        this._nome_categoria = nome_categoria;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de id_setor.
    get id_setor() {
        return this._id_setor;
    }

    // Setter para definir o valor de id_setor.
    set id_setor(id_setor) {
        this._id_setor = id_setor;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }

    // Getter para obter o valor de desconto_categoria.
    get desconto_categoria() {
        return this._desconto_categoria;
    }

    // Setter para definir o valor de desconto_categoria.
    set desconto_categoria(desconto_categoria) {
        this._desconto_categoria = desconto_categoria;
        return this;  // Retorna a instância atual para permitir encadeamento de chamadas.
    }
}

// Exporta a classe Categoria para que possa ser utilizada em outros módulos.
module.exports = Categoria;
