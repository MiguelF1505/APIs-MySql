const express = require('express');

const CategoriaControl = require('../control/CategoriaControl'); // Update to CategoriaControl
const CategoriaMiddleware = require('../middleware/CategoriaMiddleware'); // Update to CategoriaMiddleware
const JWTMiddleware = require('../middleware/JWTMiddleware');

module.exports = class CategoriaRouter {

    constructor() {
        this._router = express.Router();
        this._jwtMiddleware = new JWTMiddleware();
        this._categoriaControl = new CategoriaControl();
        this._categoriaMiddleware = new CategoriaMiddleware();
    }

    createRoutes() {
        const multer = require('multer');
        const upload = multer({ dest: 'uploads/' }); // Configura o multer para armazenar os arquivos na pasta 'uploads'

        this.router.get('/',
            this.jwtMiddleware.validate,
            this.categoriaControl.readAll // Método para ler todas as categorias
        );

        this.router.get('/:id_categoria',
            this.jwtMiddleware.validate,
            this.categoriaControl.readById // Método para ler uma categoria por ID
        );

        this.router.post('/',
            this.jwtMiddleware.validate,
            this.categoriaMiddleware.validar_NomeCategoria, // Validação do nome da categoria
            this.categoriaMiddleware.isNot_categoriaByNomeCategoria, // Verifica se a categoria já existe pelo nome
            this.categoriaControl.create // Método para criar uma nova categoria
        );

        this.router.delete('/:id_categoria',
            this.jwtMiddleware.validate,
            this.categoriaControl.delete // Método para excluir uma categoria
        );

        this.router.put('/:id_categoria',
            this.jwtMiddleware.validate,
            this.categoriaControl.update // Método para atualizar uma categoria
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

    get categoriaControl() {
        return this._categoriaControl;
    }

    set categoriaControl(newCategoriaControl) {
        this._categoriaControl = newCategoriaControl;
    }

    get categoriaMiddleware() {
        return this._categoriaMiddleware;
    }

    set categoriaMiddleware(newCategoriaMiddleware) {
        this._categoriaMiddleware = newCategoriaMiddleware;
    }
}