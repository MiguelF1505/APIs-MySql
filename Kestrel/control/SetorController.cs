// Importa namespaces necessários para o projeto
using Microsoft.AspNetCore.Mvc; // Permite o uso de funcionalidades relacionadas ao ASP.NET Core MVC
using RestAPi.Model; // Importa os modelos usados pela API

// Define o namespace do controlador
namespace RestAPi.Control
{
    // Indica que esta classe é um controlador de API
    [ApiController]
    // Define a rota base para este controlador
    [Route("/[controller]")]
    public class SetorController : ControllerBase // Classe que herda de ControllerBase, usada para criar APIs RESTful
    {
        // Define um endpoint HTTP GET para obter todos os setores
        [HttpGet("/setores/")]
        public IActionResult Get_Setores() // Método que lida com solicitações GET para "/setores/"
        {
            try
            {
                // Obtém o cabeçalho de autorização da requisição
                string authorization = Request.Headers["Authorization"].ToString();
                // Cria uma instância de middleware para validação de JWT
                JwtMiddleware jwtMiddleware = new JwtMiddleware();
                // Valida o token de autorização
                jwtMiddleware.Validar(authorization);
            }
            catch (Exception e) // Captura qualquer exceção que possa ocorrer
            {
                // Cria uma resposta de erro com status e mensagem de erro
                return Unauthorized(new { status = false, mensagem = e.Message });
            }

            // Cria uma nova instância do objeto Setor
            Setor objSetor = new Setor();
            // Cria uma resposta com os dados de setores
            var resposta = new
            {
                status = true, // Define o status como sucesso
                mensagem = "Executado com sucesso", // Mensagem de sucesso
                setores = objSetor.Read() // Chama o método Read() que retorna a lista de setores
            };
            // Retorna a resposta com status 200 OK
            return Ok(resposta);
        }

        // Define um endpoint HTTP GET para obter setor por ID
        [HttpGet("/setores/{idSetor}/")]
        public IActionResult Get_Setor_by_Id(uint idSetor) // Método que lida com solicitações GET para "/setores/{idSetor}/"
        {
            // Cria uma nova instância do objeto Setor
            Setor objSetor = new Setor { IdSetor = idSetor };
            // Cria uma resposta contendo o setor retornado pelo método ReadById()
            var resposta = new
            {
                setor = objSetor.ReadById() // Obtém os dados do setor pelo ID
            };

            // Retorna a resposta com status 200 OK
            return Ok(resposta);
        }

        // Define um endpoint HTTP POST para criar um novo setor
        [HttpPost("/setores/")]
        public IActionResult Post_Setor([FromBody] Dictionary<string, Dictionary<string, object>> jsonData) // Método que lida com solicitações POST para "/setores/"
        {
            try
            {
                // Obtém o cabeçalho de autorização da requisição
                string authorization = Request.Headers["Authorization"].ToString();
                // Cria uma instância de middleware para validação de JWT
                JwtMiddleware jwtMiddleware = new JwtMiddleware();
                // Valida o token de autorização
                jwtMiddleware.Validar(authorization);

                // Extrai os dados do setor a partir do JSON fornecido
                if (jsonData.TryGetValue("setor", out var setorData) && setorData.TryGetValue("nome_setor", out var nomeSetorObj))
                {
                    // Obtém o nome do setor
                    string nomeSetor = nomeSetorObj?.ToString() ?? string.Empty; // Usa coalescência nula para evitar nulos

                    // Verifica se nomeSetor não é nulo ou vazio
                    if (string.IsNullOrWhiteSpace(nomeSetor))
                    {
                        return BadRequest(new { mensagem = "O nome do setor não pode ser nulo ou vazio." });
                    }

                    // Cria uma nova instância do objeto Setor e atribui o nome do setor
                    Setor setor = new Setor { NomeSetor = nomeSetor };

                    // Tenta criar o novo setor chamando o método Create()
                    if (!setor.Create()) // Verifica se o método Create() falhou
                    {
                        return StatusCode(500, new { mensagem = "Erro ao salvar setor." }); // Retorna 500 em caso de erro
                    }

                    // Retorna a resposta com status 200 OK
                    return Ok(new { mensagem = "Setor criado com sucesso", setor });
                }
                else
                {
                    return BadRequest(new { mensagem = "Dados do setor não fornecidos." });
                }
            }
            catch (Exception e) // Captura qualquer exceção que possa ocorrer
            {
                // Cria uma resposta de erro com a mensagem da exceção
                return BadRequest(new { mensagem = e.Message }); // Retorna uma resposta 400 Bad Request
            }
        }

        // Define um endpoint HTTP PUT para atualizar um setor existente
        [HttpPut("/setores/{idSetor}")]
        public IActionResult Put_Setor(uint idSetor, [FromBody] Dictionary<string, Dictionary<string, object>> jsonData) // Método que lida com solicitações PUT para "/setores/{idSetor}"
        {
            try
            {
                // Obtém o cabeçalho de autorização da requisição
                string authorization = Request.Headers["Authorization"].ToString();
                // Cria uma instância de middleware para validação de JWT
                JwtMiddleware jwtMiddleware = new JwtMiddleware();
                // Valida o token de autorização
                jwtMiddleware.Validar(authorization);

                // Extrai os dados do setor a partir do JSON fornecido
                if (jsonData.TryGetValue("setor", out var setorData) && setorData.TryGetValue("nome_setor", out var nomeSetorObj))
                {
                    // Obtém o nome do setor
                    string nomeSetor = nomeSetorObj?.ToString() ?? string.Empty; // Usa coalescência nula para evitar nulos

                    // Verifica se nomeSetor não é nulo ou vazio
                    if (string.IsNullOrWhiteSpace(nomeSetor))
                    {
                        return BadRequest(new { mensagem = "O nome do setor não pode ser nulo ou vazio." });
                    }

                    // Cria uma nova instância do objeto Setor e atribui o nome do setor
                    Setor setor = new Setor { IdSetor = idSetor, NomeSetor = nomeSetor }; // Define o ID do setor a ser atualizado

                    // Tenta atualizar o setor chamando o método Update()
                    if (!setor.Update()) // Verifica se o método Update() falhou
                    {
                        return StatusCode(500, new { mensagem = "Erro ao atualizar setor." }); // Retorna 500 em caso de erro
                    }

                    // Retorna a resposta com status 200 OK
                    return Ok(new { mensagem = "Setor atualizado com sucesso", setor });
                }
                else
                {
                    return BadRequest(new { mensagem = "Dados do setor não fornecidos." });
                }
            }
            catch (Exception e) // Captura qualquer exceção que possa ocorrer
            {
                // Cria uma resposta de erro com a mensagem da exceção
                return BadRequest(new { mensagem = e.Message }); // Retorna uma resposta 400 Bad Request
            }
        }
    }
}
