// Importa o modelo Produto para verificar se o nome já existe no banco de dados.
const Produto = require('../model/Produto');
// Exporta a classe ProdutoMiddleware, que contém funções de validação para as requisições.
module.exports = class ProdutoMiddleware {
    // Método para validar o nome do produto antes de prosseguir com a criação ou atualização.
    async validar_NomeProduto(request, response, next) {

        // Recupera o nome do produto enviado no corpo da requisição (request body).
        if(request.body.produto == null){
            const objResposta = {
                status: false,
                msg: "Produto NULL."
            };
            return response.status(400).send(objResposta);
        }
        if(request.body.produto.nome_produto == null){
            const objResposta = {
                status: false,
                msg: "Nome produto NULL."
            };
            return response.status(400).send(objResposta);
        }

        const nome_produto = request.body.produto.nome_produto;
        // Verifica se o nome do produto tem menos de 3 caracteres.
        if (nome_produto.length < 3) {
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
    // Método assíncrono para verificar se já existe um produto com o mesmo nome cadastrado.
    async isNot_produtoByNomeProduto(request, response, next) {
        if(request.body.produto == null){
            const objResposta = {
                status: false,
                msg: "Produto NULL."
            };
            return response.status(400).send(objResposta);
        }
        if(request.body.produto.nome_produto == null){
            const objResposta = {
                status: false,
                msg: "Nome produto NULL."
            };
            return response.status(400).send(objResposta);
        }
        // Recupera o nome do produto enviado no corpo da requisição (request body).
        const nome_produto = request.body.produto.nome_produto;
        // Cria uma nova instância do modelo Produto.
        const objProduto = new Produto();
        // Define o nome do produto na instância do modelo.
        objProduto.nome_produto = nome_produto;
        // Verifica se o produto já existe no banco de dados chamando o método isProduto().
        const produtoExiste = await objProduto.isProdutoByNomeProduto();
        // Se o produto já existir no banco de dados, cria um objeto de resposta com o status falso e uma mensagem de erro.
        if (produtoExiste == false) {
            next(); // Chama o próximo middleware ou rota
        } else {
            const objResposta = {
                status: false,
                msg: "Não é possível cadastrar um produto com o mesmo nome de um produto existente"
            }
            response.status(400).send(objResposta);
        }
    }

    async isProdutoById(request, response, next) {

        const id_produto = request.body.id_produto

        const objProduto = new Produto();

        const produtoExiste = await objProduto.isProdutoById(id_produto);

        if (produtoExiste == true) {
            next();

        } else {
            const objResposta = {
                status: false,
                msg: "Produto Não Existe"
            }

            response.status(400).send(objResposta);
        }
    }
}
