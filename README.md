# Bug Report – Biblioteca PHP

Permite que sites capturem e enviem bugs como vídeos ou imagens diretamente para o servidor.

## Instalação

```
composer require bugadoz/bug-report
```

## Uso

```php
<?php
use Bugadoz\BugReport;

$bug = new BugReport('SUA_API_KEY', __DIR__ . '/temp/');

// Simulando um arquivo do $_FILES:
$arquivoSalvo = $bug->saveFile($_FILES['screenshot'] ?? []);

$resposta = $bug->reportBug([
    'descricao' => 'Erro ao clicar em "Enviar"',
    'url' => 'https://meusite.com/formulario'
], $arquivoSalvo);

echo $resposta;

```

## Requisitos do servidor

- PHP >= 7.4
- Extensões: curl, fileinfo
- Permissão de escrita na pasta `/uploads`
