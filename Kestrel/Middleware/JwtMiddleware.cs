// Importa o namespace que contém os modelos utilizados, incluindo MeuTokenJWT
using RestAPi.Model;

// Define a classe JwtMiddleware, responsável pela validação de tokens JWT
public class JwtMiddleware
{
    // Método que valida o token JWT, recebe uma string contendo o token
    public void Validar(string tokenString)
    {
        // Verifica se a string do token está vazia ou contém apenas espaços em branco
        if (string.IsNullOrWhiteSpace(tokenString))
        {
            // Lança uma exceção se o token estiver ausente ou em branco
            throw new Exception("Token não encontrado");
        }

        // Remove a palavra "Bearer" do token, caso ela esteja presente, e remove espaços extras
        tokenString = tokenString.Replace("Bearer", "").Trim();
        
        // Verifica se, após a remoção, o token ainda contém caracteres
        if (tokenString.Length == 0)
        {
            // Lança uma exceção se o token estiver vazio
            throw new Exception("Token Vazio");
        }

        // Cria uma nova instância da classe MeuTokenJWT, responsável por validar o token
        MeuTokenJWT meuToken = new MeuTokenJWT();

        // Verifica se o token é válido chamando o método ValidarToken
        if (meuToken.ValidarToken(tokenString) == true)
        {
            // Se o token for válido, imprime o id_setor associado ao token
            Console.WriteLine("id_setor: " + meuToken.IdSetor); // Altere aqui para usar Id_setor
        }
    }
}
