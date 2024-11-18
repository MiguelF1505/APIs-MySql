// Importa o módulo express para criação de APIs.
const express = require('express');
// Importa o modelo Produto para realizar operações relacionadas à entidade Produto.
const Produto = require('../model/Produto');
// Exporta a classe ProdutoControl, que controla as operações de CRUD (Create, Read, Update, Delete) para o Produto.
module.exports = class ProdutoControl {
    // Método assíncrono para criar um novo produto.
    async create(request, response) {
        // Cria uma nova instância do modelo Produto.
        var produto = new Produto();
        // Atribui o nome do produto passado no corpo da requisição (request body) à instância criada.
        produto.nome_produto = request.body.produto.nome_produto;
        produto.id_produto = request.body.produto.id_produto;
        produto.unidades_produto = request.body.produto.unidades_produto;
        produto.preco_produto = request.body.produto.preco_produto;
        produto.id_categoria = request.body.produto.id_categoria;
        // Chama o método create() do modelo Produto para inserir o novo produto no banco de dados.
        const isCreated = await produto.create();
        // Cria um objeto de resposta contendo o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: isCreated,
            msg: isCreated ? 'Produto criado com sucesso' : 'Erro ao criar o produto'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para excluir um produto existente.
    async delete(request, response) {
        // Cria uma nova instância do modelo Produto.
        var produto = new Produto();
        // Atribui o ID do produto passado como parâmetro na URL (request params) à instância criada.
        produto.id_produto = request.params.id_produto;
        // Chama o método delete() do modelo Produto para excluir o produto do banco de dados.
        const isDeleted = await produto.delete();
        // Cria um objeto de resposta com o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: isDeleted,
            msg: isDeleted ? 'Produto excluído com sucesso' : 'Erro ao excluir o produto'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para atualizar um produto existente.
    async update(request, response) {
        // Cria uma nova instância do modelo Produto.
        var produto = new Produto();
        // Atribui o ID e o nome do produto passados na URL e no corpo da requisição, respectivamente.
        produto.id_produto = request.params.id_produto;
        produto.nome_produto = request.body.produto.nome_produto;
        produto.unidades_produto = request.body.produto.unidades_produto;
        produto.id_categoria = request.body.produto.id_categoria;
        produto.preco_produto = request.body.produto.preco_produto;

        // Chama o método update() do modelo Produto para atualizar o produto no banco de dados.
        const isUpdated = await produto.update();
        // Cria um objeto de resposta com o código, status e a mensagem de sucesso ou erro.
        const objResposta = {
            cod: 1,
            status: true,
            msg: isUpdated ? 'Produto atualizado com sucesso' : 'Erro ao atualizar o produto'
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para obter todos os produtos.
    async readAll(request, response) {
        // Cria uma nova instância do modelo Produto.
        var produto = new Produto();
        // Chama o método readAll() para buscar todos os produtos no banco de dados.
        const resultado = await produto.readAll();
        // Cria um objeto de resposta contendo o código, status, mensagem e a lista de produtos.
        const objResposta = {
            cod: 1,
            status: true,
            msg: 'Executado com sucesso',
            produtos: resultado
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    // Método assíncrono para obter um produto pelo ID.
    async readById(request, response) {
        // Cria uma nova instância do modelo Produto.
        var produto = new Produto();
        // Atribui o ID do produto passado como parâmetro na URL (request params) à instância criada.
        produto.id_produto = request.params.id_produto;

        // Chama o método readByID() para buscar o produto pelo ID no banco de dados.
        const resultado = await produto.readByID();
        // Cria um objeto de resposta contendo o código, status, mensagem e o produto encontrado (ou não).
        const objResposta = {
            cod: 1,
            status: true,
            msg: resultado ? 'Produto encontrada' : 'Produto não encontrada',
            produto: resultado
        };
        // Envia a resposta HTTP com status 200 e o objeto de resposta.
        response.status(200).send(objResposta);
    }

    async createByCSV(request, response) {
        const multer = require('multer'); //npm install multer --save
        const csv = require('csv-parser'); //npm install csv-parser --save
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

        //#############
        const produtos = [];

        fs.readFile(request.file.path, 'utf8', (err, data) => {
            if (err) {
                console.error('Erro ao ler o arquivo CSV:', err);
                return;
            }

            const linhas = data.split('\n');
            // Assumindo que a primeira linha não é um cabeçalho e contém dados
            for (let i = 0; i < linhas.length; i++) {
                const linha = linhas[i].split(',');

                if (linha.length >= 1) { // Verifica se há pelo menos uma coluna
                    const produto = new Produto();
                    linha[0] = linha[0].trim();
                    linha[0] = linha[0].replace('\n', '');
                    linha[0] = linha[0].replace('\r', '');
                    if (linha[0].length > 5) {
                        produto.nome_produto = linha[0]; // Assumindo que a primeira coluna é 'nome'
                        produto.create();
                        produtos.push(produto);
                    }
                }
            }

            const objResposta = {
                cod: 1,
                status: true,
                msg: 'cadastrado com sucesso',
                produtos: produtos
            }
            response.status(201).send(objResposta);

            // console.log('Produtos:', produtos);
        });

        //########################################
        /*  const produtos = [];
  
          // Lê o arquivo CSV e processa linha por linha
          await fs.createReadStream(request.file.path)
              .pipe(csv())
              .on('data', (row) => {
                  // Para cada linha, cria uma instância de Produto e atribui os valores do CSV
                  const produto = new Produto();
                  produto.nome_produto = row.nome;
                  produtos.push(produto);
              })
              .on('end', async () => {
                  // Após processar o CSV, faz a inserção de cada produto
                  try {
                      for (let i = 0; i < produtos.length; i++) {
                          await produtos[i].create();
                      }
                      // Responde com sucesso após todos os produtos serem cadastrados
                      return response.status(200).send({
                          cod: 1,
                          status: true,
                          msg: `${produtos.length} produtos cadastrados com sucesso`
                      });
                  } catch (error) {
                      return response.status(500).send({
                          cod: 0,
                          status: false,
                          msg: "Erro ao cadastrar produtos",
                          error: error.message
                      });
                  }
              });
              */
    }

};
