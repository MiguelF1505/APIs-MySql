// Importa o módulo express para criação de APIs.
const express = require('express');
// Importa o modelo Categoria para realizar operações relacionadas à entidade Categoria.
const Categoria = require('../model/Categoria');
// Exporta a classe CategoriaControl, que controla as operações de CRUD (Create, Read, Update, Delete) para o Categoria.
module.exports = class CategoriaControl {
    // Método assíncrono para criar um novo categoria.
    async create(request, response) {
        // Cria uma nova instância do modelo Categoria.
        var categoria = new Categoria();
        // Atribui o nome do categoria passado no corpo da requisição (request body) à instância criada.
        categoria.nome_categoria = request.body.categoria.nome_categoria;
        categoria.id_setor = request.body.categoria.id_setor;
        categoria.desconto_categoria = request.body.categoria.desconto_categoria;
        // Chama o método create() do modelo Categoria para inserir o novo categoria no banco de dados.
        const isCreated = await categoria.create();
        // Cria um objeto de resposta contendo o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: isCreated,
            msg: isCreated ? 'Categoria criado com sucesso' : 'Erro ao criar o categoria'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para excluir um categoria existente.
    async delete(request, response) {
        // Cria uma nova instância do modelo Categoria.
        var categoria = new Categoria();
        // Atribui o ID do categoria passado como parâmetro na URL (request params) à instância criada.
        categoria.id_categoria = request.params.id_categoria;
        // Chama o método delete() do modelo Categoria para excluir o categoria do banco de dados.
        const isDeleted = await categoria.delete();
        // Cria um objeto de resposta com o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: isDeleted,
            msg: isDeleted ? 'Categoria excluído com sucesso' : 'Erro ao excluir o categoria'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para atualizar um categoria existente.
    async update(request, response) {
        // Cria uma nova instância do modelo Categoria.
        var categoria = new Categoria();
        // Atribui o ID e o nome do categoria passados na URL e no corpo da requisição, respectivamente.
        categoria.id_categoria = request.params.id_categoria;
        categoria.nome_categoria = request.body.categoria.nome_categoria;
        categoria.id_setor = request.body.categoria.id_setor;
        categoria.desconto_categoria = request.body.categoria.desconto_categoria;

        // Chama o método update() do modelo Categoria para atualizar o categoria no banco de dados.
        const isUpdated = await categoria.update();
        // Cria um objeto de resposta com o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: true,
            msg: isUpdated ? 'Categoria atualizado com sucesso' : 'Erro ao atualizar o categoria'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para obter todos os categorias.
    async readAll(request, response) {
        // Cria uma nova instância do modelo Categoria.
        var categoria = new Categoria();
        // Chama o método readAll() para buscar todos os categorias no banco de dados.
        const resultado = await categoria.readAll();
        // Cria um objeto de resposta contendo o código, status, mensagem e a lista de categorias.
        const objResposta = {
            cod: 1,
            status: true,
            msg: 'Executado com sucesso',
            categorias: resultado
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para obter um categoria pelo ID.
    async readById(request, response) {
        // Cria uma nova instância do modelo Categoria.
        var categoria = new Categoria();
        // Atribui o ID do categoria passado como parâmetro na URL (request params) à instância criada.
        categoria.id_categoria = request.params.id_categoria;

        // Chama o método readByID() para buscar o categoria pelo ID no banco de dados.
        const resultado = await categoria.readByID();
        // Cria um objeto de resposta contendo o código, status, mensagem e o categoria encontrado (ou não).
        const objResposta = {
            cod: 1,
            status: true,
            msg: resultado ? 'Categoria encontrada' : 'Categoria não encontrada',
            categoria: resultado
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    async createByJSON(request, response) {
        const multer = require('multer'); // npm install multer --save
        const fs = require('fs');
        const upload = multer({ dest: 'uploads/' });
    
        // Verifica se o arquivo foi enviado
        if (!request.file) {
            return response.status(400).send({
                cod: 0,
                status: false,
                msg: "Nenhum arquivo foi enviado."
            });
        }
    
        const setores = [];
    
        fs.readFile(request.file.path, 'utf8', async (err, data) => {
            if (err) {
                console.error('Erro ao ler o arquivo JSON:', err);
                return response.status(500).send({
                    cod: 0,
                    status: false,
                    msg: "Erro ao ler o arquivo JSON.",
                    error: err.message
                });
            }
    
            try {
                const jsonData = JSON.parse(data); // Faz o parse do JSON
                if (jsonData.setores && Array.isArray(jsonData.setores)) {
                    for (const item of jsonData.setores) {
                        const setor = new Setor(); // Presumindo que você tenha uma classe Setor
                        setor.nome_setor = item.nome_setor; // Atribui o nome do setor
                        await setor.create(); // Salva o setor no banco de dados
                        setores.push(setor);
                    }
                } else {
                    return response.status(400).send({
                        cod: 0,
                        status: false,
                        msg: "Formato do JSON inválido."
                    });
                }
    
                const objResposta = {
                    cod: 1,
                    status: true,
                    msg: 'Setores cadastrados com sucesso',
                    setores: setores
                };
                response.status(201).send(objResposta);
            } catch (parseError) {
                console.error('Erro ao fazer o parse do JSON:', parseError);
                return response.status(500).send({
                    cod: 0,
                    status: false,
                    msg: "Erro ao processar o arquivo JSON.",
                    error: parseError.message
                });
            }
        });
    }

};
