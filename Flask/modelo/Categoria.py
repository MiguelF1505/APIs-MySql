from modelo.Banco import Banco
from mysql.connector import Error

class Categoria:
    def __init__(self):
        self._id_categoria = None
        self._nome_categoria = None
        self._desconto_categoria = None
        self._id_setor = None

    def create(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "INSERT INTO categorias (nome_categoria, desconto_categoria, id_setor) VALUES (%s, %s, %s)"
                cursor.execute(sql, (self._nome_categoria, self._desconto_categoria, self._id_setor))
                conexao.commit()
                self._id_categoria = cursor.lastrowid
                return True
            except Error as e:
                print(f"Erro ao criar categoria: {e}")
                return False
            finally:
                cursor.close()

    def delete(self, id):
        conexao = Banco.getConexao()
        
        if conexao:
            cursor = None
            try:
                cursor = conexao.cursor()
                sql = "DELETE FROM categorias WHERE id_categoria = %s"
                cursor.execute(sql, (id,))
                conexao.commit()
                return cursor.rowcount > 0  # Retorna True se alguma linha foi afetada
            except Error as e:
                print(f"Erro ao deletar categoria: {e}")
                return False
            finally:
                if cursor:
                    cursor.close()

    def update(self):
        conexao = Banco.getConexao()
        
        if conexao:
            cursor = None
            try:
                cursor = conexao.cursor()
                sql = "UPDATE categorias SET nome_categoria = %s, desconto_categoria = %s, id_setor = %s WHERE id_categoria = %s"
                cursor.execute(sql, (self._nome_categoria, self._desconto_categoria, self._id_setor, self._id_categoria))
                conexao.commit()
                return cursor.rowcount > 0  # Retorna True se alguma linha foi afetada
            except Error as e:
                print(f"Erro ao atualizar categoria: {e}")
                return False
            finally:
                if cursor:
                    cursor.close()  # Garante que o cursor seja fechado

    def is_categoria_by_nome(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "SELECT COUNT(*) FROM categorias WHERE nome_categoria = %s"
                cursor.execute(sql, (self._nome_categoria,))
                result = cursor.fetchone()
                return result[0] > 0
            except Error as e:
                print(f"Erro ao verificar categoria por nome: {e}")
                return False
            finally:
                cursor.close()

    def is_categoria_by_id(self, id_categoria):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "SELECT COUNT(*) FROM categorias WHERE id_categoria = %s"
                cursor.execute(sql, (id_categoria,))
                result = cursor.fetchone()
                return result[0] > 0 
            except Error as e:
                print(f"Erro ao verificar categoria por ID: {e}")
                return False
            finally:
                cursor.close()

    def read_all(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor(dictionary=True)
                sql = "SELECT * FROM categorias ORDER BY nome_categoria"
                cursor.execute(sql)
                return cursor.fetchall()
            except Error as e:
                print(f"Erro ao obter categorias: {e}")
                return []
            finally:
                cursor.close()

    def read_by_id(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor(dictionary=True)
                sql = "SELECT * FROM categorias WHERE id_categoria = %s"
                cursor.execute(sql, (self._id_categoria,))
                result = cursor.fetchone()
                return result 
            except Error as e:
                print(f"Erro ao obter categoria por ID: {e}")
                return None
            finally:
                cursor.close()

    @property
    def id_categoria(self):
        return self._id_categoria

    @id_categoria.setter
    def id_categoria(self, value):
        self._id_categoria = value

    @property
    def nome_categoria(self):
        return self._nome_categoria

    @nome_categoria.setter
    def nome_categoria(self, value):
        self._nome_categoria = value

    @property
    def desconto_categoria(self):
        return self._desconto_categoria

    @desconto_categoria.setter
    def desconto_categoria(self, value):
        self._desconto_categoria = value

    @property
    def id_setor(self):
        return self._id_setor

    @id_setor.setter
    def id_setor(self, value):
        self._id_setor = value
