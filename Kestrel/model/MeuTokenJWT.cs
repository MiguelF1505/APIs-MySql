using System;
using System.Collections.Generic;
using System.IdentityModel.Tokens.Jwt;
using System.Linq;
using System.Security.Claims;
using Microsoft.IdentityModel.Tokens;

namespace RestAPi.Model
{
    public class MeuTokenJWT
    {
    public string? IdSetor { get; private set; } // Agora é anulável

        private const string SecretKey = "x9S4q0v+V0IjvHkG20uAxaHx1ijj+q1HWjHKv+ohxp/oK+77qyXkVj/l4QYHHTF3";
        private const string Algorithm = SecurityAlgorithms.HmacSha256;
        private const string Issuer = "http://localhost";
        private const string Audience = "http://localhost";
        private static readonly TimeSpan TokenDuration = TimeSpan.FromDays(30);

        public string GerarToken(Dictionary<string, object> claims)
        {
            var securityKey = new SymmetricSecurityKey(System.Text.Encoding.UTF8.GetBytes(SecretKey));
            var credentials = new SigningCredentials(securityKey, Algorithm);

            var claimsList = new List<Claim>
            {
                new Claim(JwtRegisteredClaimNames.Iss, Issuer),
                new Claim(JwtRegisteredClaimNames.Aud, Audience),
                new Claim(JwtRegisteredClaimNames.Sub, "acesso_sistema"),
                new Claim(JwtRegisteredClaimNames.Iat, DateTime.UtcNow.ToString()),
                new Claim(JwtRegisteredClaimNames.Exp, DateTime.UtcNow.Add(TokenDuration).ToString()),
                new Claim(JwtRegisteredClaimNames.Jti, Guid.NewGuid().ToString())
            };

            // Adiciona claims adicionais, garantindo que o valor não seja nulo
            claimsList.AddRange(claims.Select(claim =>
                new Claim(claim.Key, claim.Value?.ToString() ?? string.Empty))); // Fornecendo valor padrão para nulos

            var tokenHandler = new JwtSecurityTokenHandler();
            var token = tokenHandler.CreateToken(new SecurityTokenDescriptor
            {
                Subject = new ClaimsIdentity(claimsList),
                Expires = DateTime.UtcNow.Add(TokenDuration),
                SigningCredentials = credentials
            });

            return tokenHandler.WriteToken(token);
        }

        public bool ValidarToken(string tokenString)
        {
            if (string.IsNullOrWhiteSpace(tokenString))
            {
                return false;
            }

            tokenString = tokenString.Replace("Bearer", "").Trim();
            var tokenHandler = new JwtSecurityTokenHandler();

            try
            {
                tokenHandler.ValidateToken(tokenString, new TokenValidationParameters
                {
                    ValidateIssuerSigningKey = true,
                    IssuerSigningKey = new SymmetricSecurityKey(System.Text.Encoding.UTF8.GetBytes(SecretKey)),
                    ValidateIssuer = true,
                    ValidIssuer = Issuer,
                    ValidateAudience = true,
                    ValidAudience = Audience,
                    ClockSkew = TimeSpan.Zero
                }, out var validatedToken);

                if (validatedToken is JwtSecurityToken jwtToken)
                {
                    IdSetor = jwtToken.Claims.FirstOrDefault(c => c.Type == "id_setor")?.Value;
                }

                return true;
            }
            catch
            {
                return false;
            }
        }
    }
}
