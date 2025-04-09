# Bug Report – Biblioteca PHP

Permite que sites capturem e enviem bugs como vídeos ou imagens diretamente para o servidor.

## Instalação

```
composer require bugadoz/bug-report
```

## Uso

```php
use Bugadoz\BugReport;

$bug = new BugReport('SUA_API_KEY', __DIR__ . '/uploads');
```

## Requisitos do servidor

- PHP >= 7.4
- Extensões: curl, fileinfo
- Permissão de escrita na pasta `/uploads`
