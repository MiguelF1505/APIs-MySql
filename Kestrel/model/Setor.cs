using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;

namespace RestAPi.Model
{
    // Classe Setor que representa a entidade "Setor" no banco de dados
    public class Setor
    {
        // Propriedades públicas que representam as colunas da tabela Setores
        public uint IdSetor { get; set; }  // Armazena o ID do setor
        public string? NomeSetor { get; set; }  // Armazena o nome do setor

        // Método para criar um novo registro na tabela Setores
        public bool Create()
        {
            try
            {
                // Cria um comando SQL para inserir um novo setor na tabela
                MySqlCommand mysqlCommand = new MySqlCommand("INSERT INTO setores (nome_setor) VALUES (@nomeSetor)", Banco.GetConnection());

                // Adiciona o valor da propriedade NomeSetor como parâmetro para a consulta SQL
                mysqlCommand.Parameters.AddWithValue("@nomeSetor", NomeSetor);

                // Executa a consulta e retorna o número de linhas afetadas
                int itensInseridos = mysqlCommand.ExecuteNonQuery();

                // Verifica se algum item foi inserido
                if (itensInseridos > 0)
                {
                    // Recupera o último ID inserido no banco de dados
                    uint idSetorInserido = Convert.ToUInt32(mysqlCommand.LastInsertedId);

                    // Atribui o ID recuperado à propriedade IdSetor do objeto
                    this.IdSetor = idSetorInserido;
                    return true; // Retorna true se a inserção foi bem-sucedida
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao criar setor: " + ex.Message);
                return false;
            }
            return false;
        }

        // Método para ler todos os registros da tabela Setores
        public List<Setor> Read()
        {
            List<Setor> setores = new List<Setor>();

            try
            {
                MySqlCommand mysqlCommand = new MySqlCommand("SELECT * FROM setores", Banco.GetConnection());
                MySqlDataReader matrizRegistros = mysqlCommand.ExecuteReader();

                while (matrizRegistros.Read())
                {
                    Setor setor = new Setor
                    {
                        IdSetor = matrizRegistros.GetUInt32("id_setor"),
                        NomeSetor = matrizRegistros.GetString("nome_setor")
                    };

                    setores.Add(setor);
                }
                matrizRegistros.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao ler setores: " + ex.Message);
            }
            return setores;
        }

        // Método para ler um registro específico pelo Id
        public Setor ReadById()
        {
            Setor setor = new Setor();
            try
            {
                MySqlCommand mySqlCommand = new MySqlCommand("SELECT * FROM setores WHERE id_setor = @idSetor", Banco.GetConnection());
                mySqlCommand.Parameters.AddWithValue("@idSetor", this.IdSetor);
                MySqlDataReader matrizRegistros = mySqlCommand.ExecuteReader();

                if (matrizRegistros.Read())
                {
                    setor = new Setor
                    {
                        IdSetor = matrizRegistros.GetUInt32("id_setor"),
                        NomeSetor = matrizRegistros.GetString("nome_setor")
                    };
                }
                matrizRegistros.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao ler setor por ID: " + ex.Message);
            }
            return setor;
        }

        // Método para atualizar um registro existente
        public bool Update()
        {
            try
            {
                MySqlCommand mySqlCommand = new MySqlCommand("UPDATE setores SET nome_setor = @nomeSetor WHERE id_setor = @idSetor", Banco.GetConnection());
                mySqlCommand.Parameters.AddWithValue("@idSetor", this.IdSetor);
                mySqlCommand.Parameters.AddWithValue("@nomeSetor", this.NomeSetor);

                int qtdSetoresAtualizados = mySqlCommand.ExecuteNonQuery();
                return qtdSetoresAtualizados > 0;
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao atualizar setor: " + ex.Message);
                return false;
            }
        }

        // Método para excluir um registro existente
        public bool Delete()
        {
            try
            {
                MySqlCommand command = new MySqlCommand("DELETE FROM setores WHERE id_setor = @idSetor", Banco.GetConnection());
                command.Parameters.AddWithValue("@idSetor", this.IdSetor);

                int qtdSetoresExcluidos = command.ExecuteNonQuery();
                return qtdSetoresExcluidos > 0;
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao excluir setor: " + ex.Message);
                return false;
            }
        }

        // Método para verificar se um setor com o mesmo nome já existe no banco de dados
        public bool IsSetorByNomeSetor(string nomeSetor)
        {
            bool existe = false;
            try
            {
                MySqlCommand mySqlCommand = new MySqlCommand("SELECT COUNT(*) as qtd FROM setores WHERE nome_setor = @nomeSetor", Banco.GetConnection());
                mySqlCommand.Parameters.AddWithValue("@nomeSetor", nomeSetor);

                object resultado = mySqlCommand.ExecuteScalar();

                if (resultado != null)
                {
                    int qtd = Convert.ToInt32(resultado);
                    existe = qtd > 0;
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("Erro ao verificar se o setor já existe: " + ex.Message);
            }
            return existe;
        }
    }
}
