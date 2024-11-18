using MySql.Data.MySqlClient;

namespace RestAPi.Model
{
    public static class Banco
    {
        private const string Host = "127.0.0.1";
        private const string User = "root";
        private const string Password = "";
        private const string DatabaseName = "loja";
        private const string Port = "3306";

        private static MySqlConnection? CONEXAO;

        private static void Connect()
        {
            string connectionString = $"Server={Host};Database={DatabaseName};User ID={User};Password={Password};Port={Port};";
            CONEXAO = new MySqlConnection(connectionString);
            CONEXAO.Open();
        }

        public static MySqlConnection GetConnection()
        {
            if (CONEXAO == null || CONEXAO.State != System.Data.ConnectionState.Open)
            {
                Connect();
            }
            return CONEXAO ?? throw new InvalidOperationException("Não foi possível estabelecer a conexão com o banco de dados.");
        }
    }
}
