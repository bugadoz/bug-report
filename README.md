# 📦 bugadoz-php-sdk

SDK oficial do [bugadoz.dev](https://bugadoz.dev) — envie logs e relatórios de erros automaticamente do seu projeto PHP com uma única linha de código.

> Geração de gráficos, relatórios, estatísticas e colaboração com a comunidade de desenvolvedores em tempo real.

---

## 🚀 Instalação

Instale via Composer:

```bash
composer require bugadoz/sdk


---

🔐 Privacidade dos Logs

A SDK permite definir o nível de privacidade dos relatórios:

Você define isso no segundo parâmetro do construtor:

$bug = new BugReport('SUA_API_KEY', 'private');


---

✨ Como Usar

use Bugadoz\BugReport;

// Inicialização com chave da API e privacidade (opcional)
$bug = new BugReport('SUA_API_KEY', 'public');

// Envio do log
$resposta = $bug->reportBug([
    'descricao' => 'Erro ao carregar usuários na dashboard',
    // Parâmetros opcionais abaixo:
    // 'url' => 'https://meusite.com/dashboard',
    // 'navegador' => $_SERVER['HTTP_USER_AGENT'],
    // 'sistema' => PHP_OS
]);

if ($resposta) {
    echo "Log enviado com sucesso!";
} else {
    echo "Falha ao enviar log.";
}


---

📋 Parâmetros Aceitos


---

🛠 Requisitos

PHP 8.0 ou superior

Extensão cURL habilitada



---

📈 Funcionalidades

Envio automático de logs para bugadoz.dev

Integração com relatórios e gráficos da plataforma

Suporte a três níveis de visibilidade (público, privado, teste)

Captura automática de dados como URL, sistema e navegador

Ideal para sistemas web, APIs e dashboards internos



---

📄 Licença

MIT — Livre para usar, modificar e distribuir.


---

Feito com 💙 pela equipe bugadoz.dev