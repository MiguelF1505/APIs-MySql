const express = require('express');

const ProdutoControl = require('../control/ProdutoControl'); // Update to ProdutoControl
const ProdutoMiddleware = require('../middleware/ProdutoMiddleware'); // Update to ProdutoMiddleware
const JWTMiddleware = require('../middleware/JWTMiddleware');

module.exports = class ProdutoRouter {

    constructor() {
        this._router = express.Router();
        this._jwtMiddleware = new JWTMiddleware();
        this._produtoControl = new ProdutoControl();
        this._produtoMiddleware = new ProdutoMiddleware();
    }

    createRoutes() {
        const multer = require('multer');
        const upload = multer({ dest: 'uploads/' }); // Configura o multer para armazenar os arquivos na pasta 'uploads'

        this.router.get('/',
            this.jwtMiddleware.validate,
            this.produtoControl.readAll // Método para ler todas as produtos
        );

        this.router.get('/:id_produto',
            this.jwtMiddleware.validate,
            this.produtoControl.readById // Método para ler uma produto por ID
        );

        this.router.post('/',
            this.jwtMiddleware.validate,
            this.produtoMiddleware.validar_NomeProduto, // Validação do nome da produto
            this.produtoMiddleware.isNot_produtoByNomeProduto, // Verifica se a produto já existe pelo nome
            this.produtoControl.create // Método para criar uma nova produto
        );

        this.router.delete('/:id_produto',
            this.jwtMiddleware.validate,
            this.produtoControl.delete // Método para excluir uma produto
        );

        this.router.put('/:id_produto',
            this.jwtMiddleware.validate,
            this.produtoControl.update // Método para atualizar uma produto
        );

        return this.router;
    }

    get router() {
        return this._router;
    }

    set router(newRouter) {
        this._router = newRouter;
    }

    get jwtMiddleware() {
        return this._jwtMiddleware;
    }

    set jwtMiddleware(newJwtMiddleware) {
        this._jwtMiddleware = newJwtMiddleware;
    }

    get produtoControl() {
        return this._produtoControl;
    }

    set produtoControl(newProdutoControl) {
        this._produtoControl = newProdutoControl;
    }

    get produtoMiddleware() {
        return this._produtoMiddleware;
    }

    set produtoMiddleware(newProdutoMiddleware) {
        this._produtoMiddleware = newProdutoMiddleware;
    }
}