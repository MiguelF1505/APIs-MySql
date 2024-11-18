// Importa o namespace contendo os modelos, incluindo o modelo Setor
using RestAPi.Model;

// Define a classe SetorMiddleware, responsável por validações relacionadas a setores
public class SetorMiddleware
{
    // Método para validar o nome do setor, recebendo um dicionário de dados JSON
    public bool ValidarNomeSetor(Dictionary<string, Dictionary<string, object>> jsonData)
    {
        // Verifica se o JSON contém a chave "setor"
        if (!jsonData.ContainsKey("setor"))
        {
            // Lança uma exceção se a chave "setor" não estiver presente
            throw new Exception("Setor não fornecido");
        }
        
        // Obtém os dados do setor a partir do JSON
        var setorData = jsonData["setor"];

        // Verifica se o objeto setor contém a chave "nome_setor"
        if (!setorData.ContainsKey("nome_setor"))
        {
            // Lança uma exceção se o campo "nome_setor" não estiver presente
            throw new Exception("Nome do Setor não fornecido");
        }
        
        // Tenta converter o valor de "nome_setor" para string
        if (setorData["nome_setor"] is string nomeSetor)
        {
            // Verifica se o nome do setor possui pelo menos 5 caracteres
            if (nomeSetor.Length < 5)
            {
                // Lança uma exceção se o nome do setor for muito curto
                throw new Exception("O nome do setor deve possuir pelo menos 5 caracteres");
            }
        }
        else
        {
            // Lança uma exceção se o valor não puder ser convertido para string
            throw new Exception("O nome do setor deve ser uma string válida");
        }

        // Se todas as validações forem bem-sucedidas, retorna true
        return true;
    }

    // Método para verificar se já existe um setor com o mesmo nome
    public bool IsNotSetor(string nomeSetor)
    {
        // Verifica se o nome do setor é nulo ou vazio
        if (string.IsNullOrWhiteSpace(nomeSetor))
        {
            throw new Exception("O nome do setor não pode ser nulo ou vazio.");
        }

        // Cria uma nova instância de Setor para realizar a verificação
        Setor setor = new Setor();

        // Chama o método IsSetorByNomeSetor para verificar se o setor já existe
        if (setor.IsSetorByNomeSetor(nomeSetor) == true)
        {
            // Lança uma exceção se o nome do setor já estiver registrado
            throw new Exception("Já existe um setor com o nome informado");
        }

        // Retorna true se não houver um setor com o nome informado
        return true;
    }
}
