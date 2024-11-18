from flask import Flask, jsonify, request
from modelo.Categoria import Categoria

class CategoriaController:
    def __init__(self):
        self._categoria = Categoria()
    
    def validar_nome_categoria(self):
        if self._categoria.nome_categoria is None:
            raise ValueError("O nome da categoria não pode ser vazio")
        if len(self._categoria.nome_categoria) < 3:
            raise ValueError("O nome da categoria deve ter pelo menos 3 caracteres.")
    
    def read_all(self):
        categorias = self._categoria.read_all()
        if categorias is not None:
            return jsonify(categorias), 200
        else:
            return jsonify({"message": "Não foi possível obter as categorias"}), 500
    
    def read_by_id(self, id):
        self._categoria.id_categoria = id;
        categoria_data = self._categoria.read_by_id()
        if categoria_data:
            return jsonify(categoria_data), 200
        else:
            return jsonify({"message": "Categoria não encontrada"}), 404
    
    def create_control(self):
        self.validar_nome_categoria()
        if self.categoria.create():
            return jsonify({
                "id_categoria": self.categoria._id_categoria,
                "nome_categoria": self.categoria._nome_categoria,
                "id_setor": self.categoria._id_setor,
                "desconto_categoria": self.categoria._desconto_categoria
            }), 201
        return jsonify({"message": "Não foi possível criar a categoria"}), 500
    
    def update(self):
        self.validar_nome_categoria()
        linhas_afetadas = self._categoria.update()
        if linhas_afetadas > 0:
            return jsonify({
                "id_categoria": self._categoria.id_categoria,
                "nome_categoria": self._categoria.nome_categoria,
                "id_setor": self._categoria.id_setor,
                "desconto_categoria": self._categoria.desconto_categoria
            }), 200
        else:
            return jsonify({"message": "Não foi possível atualizar a categoria"}), 500
    
    def delete(self, id):  # Adicionado "self" como primeiro parâmetro
        linhas_afetadas = self._categoria.delete(id)
        if linhas_afetadas > 0:
            return jsonify({"message": "Categoria excluída com sucesso"}), 200
        else:
            return jsonify({"message": "Categoria não encontrada"}), 404

    # Getter para categoria
    @property
    def categoria(self):
        return self._categoria
    
    # Setter para categoria
    @categoria.setter
    def categoria(self, value):
        self._categoria = value
