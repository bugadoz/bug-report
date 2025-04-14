
# 🐞 Bugadoz - BugReport PHP

Relate automaticamente erros do seu sistema PHP com envio para a plataforma [bugadoz.dev](https://bugadoz.dev). Esta biblioteca permite capturar e reportar erros com facilidade e organização.

---

## ⚙️ Como usar

### Instalação

Você pode incluir a classe manualmente ou usar um autoloader compatível com PSR-4.

```php
require_once 'BugReport.php'; // Ou use um autoloader
use Bugadoz\BugReport;
```

### Exemplo de uso

```php
$bug = new BugReport('SUA_API_KEY', 'public', 'feedback');

// Reportando um bug manualmente
$bug->reportBug([
    'descricao' => 'Erro ao salvar usuário',
]);

// Captura automática de erros, exceções e fatal errors
$bug->inicializarCapturaAutomatica();
```

---

## 📥 Parâmetros Aceitos

### `__construct(string $apiKey, string $privacy, string $type)`

| Parâmetro   | Tipo   | Obrigatório | Descrição |
|-------------|--------|-------------|-----------|
| `apiKey`    | string | ✅ Sim      | Sua chave de autenticação gerada no painel da [bugadoz.dev](https://bugadoz.dev). |
| `privacy`   | string | ✅ Sim      | Define se o relatório de erro será público ou privado. Valores aceitos: `public` ou `private`. |
| `type`      | string | ✅ Sim      | Define o nível de visibilidade do caminho do arquivo no relatório. <br>Valores aceitos: `onlyme`, `feedback` ou `test`. |

---

### 🔐 Detalhes dos Parâmetros

#### 🔑 apiKey
Sua chave única de autenticação com a API da bugadoz.dev. É obrigatória para enviar qualquer relatório.

#### 🕶️ privacy
Define se o erro reportado poderá ser visualizado publicamente:
- `public` – Qualquer pessoa poderá visualizar o erro.
- `private` – Apenas você terá acesso ao erro no painel.

#### 📂 type
Define quem poderá ver o **caminho do arquivo** que gerou o erro:
- `onlyme` – Apenas você poderá ver os caminhos dos arquivos (modo mais seguro).
- `feedback` – Caminhos são visíveis publicamente (para relatórios colaborativos).
- `test` – Modo de testes, pode ser usado em ambientes de desenvolvimento.

---

## 🧪 Captura Automática de Erros

Use `inicializarCapturaAutomatica()` para ativar o envio automático de:
- Erros (`set_error_handler`)
- Exceções (`set_exception_handler`)
- Fatal errors (`register_shutdown_function`)

```php
$bug->inicializarCapturaAutomatica();
```

---

## 📁 Armazenamento local de erros

Os erros são salvos automaticamente em arquivos `.txt` dentro da pasta `erros/`, caso não seja possível enviá-los para a API.

---

## 📬 Suporte

Acesse [https://bugadoz.dev](https://bugadoz.dev) para:
- Gerar sua API Key
- Acompanhar seus relatórios
- Compartilhar bugs com sua equipe
