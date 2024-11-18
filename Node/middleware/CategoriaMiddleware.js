// Importa o modelo Categoria para verificar se o nome já existe no banco de dados.
const Categoria = require('../model/Categoria');
// Exporta a classe CategoriaMiddleware, que contém funções de validação para as requisições.
module.exports = class CategoriaMiddleware {
    // Método para validar o nome do categoria antes de prosseguir com a criação ou atualização.
    async validar_NomeCategoria(request, response, next) {

        // Recupera o nome do categoria enviado no corpo da requisição (request body).
        if(request.body.categoria == null){
            const objResposta = {
                status: false,
                msg: "Categoria NULL."
            };
            return response.status(400).send(objResposta);
        }
        if(request.body.categoria.nome_categoria == null){
            const objResposta = {
                status: false,
                msg: "Nome categoria NULL."
            };
            return response.status(400).send(objResposta);
        }

        const nome_categoria = request.body.categoria.nome_categoria;
        // Verifica se o nome do categoria tem menos de 3 caracteres.
        if (nome_categoria.length < 3) {
            // Se o nome for inválido, cria um objeto de resposta com o status falso e a mensagem de erro.
            const objResposta = {
                status: false,
                msg: "O nome deve ter mais do que 3 letras"
            }
            // Envia a resposta com status HTTP 400 e a mensagem de erro.
            response.status(400).send(objResposta);
        } else {
            // Caso o nome seja válido, chama o próximo middleware ou a rota definida.
            next(); // Chama o próximo middleware ou rota
        }
    }
    // Método assíncrono para verificar se já existe um categoria com o mesmo nome cadastrado.
    async isNot_categoriaByNomeCategoria(request, response, next) {
        if(request.body.categoria == null){
            const objResposta = {
                status: false,
                msg: "Categoria NULL."
            };
            return response.status(400).send(objResposta);
        }
        if(request.body.categoria.nome_categoria == null){
            const objResposta = {
                status: false,
                msg: "Nome categoria NULL."
            };
            return response.status(400).send(objResposta);
        }
        // Recupera o nome do categoria enviado no corpo da requisição (request body).
        const nome_categoria = request.body.categoria.nome_categoria;
        // Cria uma nova instância do modelo Categoria.
        const objCategoria = new Categoria();
        // Define o nome do categoria na instância do modelo.
        objCategoria.nome_categoria = nome_categoria;
        // Verifica se o categoria já existe no banco de dados chamando o método isCategoria().
        const categoriaExiste = await objCategoria.isCategoriaByNomeCategoria();
        // Se o categoria já existir no banco de dados, cria um objeto de resposta com o status falso e uma mensagem de erro.
        if (categoriaExiste == false) {
            next(); // Chama o próximo middleware ou rota
        } else {
            const objResposta = {
                status: false,
                msg: "Não é possível cadastrar um categoria com o mesmo nome de um categoria existente"
            }
            response.status(400).send(objResposta);
        }
    }

    async isCategoriaById(request, response, next) {

        const id_categoria = request.body.id_categoria

        const objCategoria = new Categoria();

        const categoriaExiste = await objCategoria.isCategoriaById(id_categoria);

        if (categoriaExiste == true) {
            next();

        } else {
            const objResposta = {
                status: false,
                msg: "Categoria Não Existe"
            }

            response.status(400).send(objResposta);
        }
    }
}
