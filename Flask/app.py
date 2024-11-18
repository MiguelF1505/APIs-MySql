from flask import Flask, jsonify, request, Response
from Controle.CategoriaController import CategoriaController

app = Flask("rest_api")

# Função auxiliar para lidar com validações
def handle_validation_error(e):
    return jsonify({"erro": str(e)}), 400

# Endpoint GET /Categorias
@app.route('/categorias/', methods=['GET'])
def readAll():
    try:
        controller = CategoriaController()
        return controller.read_all()
    except ValueError as e:
        return handle_validation_error(e)
 
# Endpoint GET /Categorias/<int:id>
@app.route('/categorias/<int:id>', methods=['GET'])
def readById(id):
    try:
        controller = CategoriaController()
        return controller.read_by_id(id)
    except ValueError as e:
        return handle_validation_error(e)

# Endpoint POST /Categorias
@app.route('/categorias/', methods=['POST'])
def create():
    body = request.get_json()
    if not body or 'categoria' not in body:
        return jsonify({"erro": "Dados da Categoria incompletos"}), 400
    
    categoria_data = body['categoria']
    controller = CategoriaController()
    controller.categoria.nome_categoria = categoria_data.get('nome_categoria')
    controller.categoria.id_setor = categoria_data.get('id_setor')
    controller.categoria.desconto_categoria = categoria_data.get('desconto_categoria')
    
    # Chame create_control e retorne o resultado diretamente
    return controller.create_control()

# Endpoint PUT /Categorias/<int:id>
@app.route('/categorias/<int:id>', methods=['PUT'])
def update_category(id):
    try:
        body = request.get_json()
        
        # Validação do JSON recebido
        if not body or 'categoria' not in body or 'nome_categoria' not in body['categoria']:
            return jsonify({"erro": "Dados da Categoria incompletos"}), 400
        
        # Criação do controlador e atribuição dos valores
        controller = CategoriaController()
        controller.categoria.nome_categoria = body['categoria']['nome_categoria']
        controller.categoria.id_setor = body['categoria'].get('id_setor')  # Usando get para evitar KeyError
        controller.categoria.desconto_categoria = body['categoria'].get('desconto_categoria')
        controller.categoria.id_categoria = id
        
        # Chamada do método update e retorno do resultado
        if controller.update():
            return jsonify({"message": "Categoria atualizada com sucesso"}), 200
        else:
            return jsonify({"message": "Não foi possível atualizar a categoria"}), 500
    
    except Exception as e:
        return jsonify({"erro": str(e)}), 500  # Retorna erro genérico em caso de exceção

# Endpoint DELETE /Categorias/<int:id>
@app.route('/categorias/<int:id>', methods=['DELETE'])
def delete(id):
    try:
        controller = CategoriaController()
        # Chame o método delete na instância de categoria
        linhas_afetadas = controller.categoria.delete(id)  # Corrigido para 'categoria'
        
        if linhas_afetadas:
            return jsonify({"message": "Categoria deletada com sucesso"}), 200
        else:
            return jsonify({"message": "Categoria não encontrada"}), 404
    except ValueError as e:
        return handle_validation_error(e)
    except Exception as e:
        return jsonify({"erro": str(e)}), 500  # Captura qualquer outra exceção

# Inicia o servidor
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
