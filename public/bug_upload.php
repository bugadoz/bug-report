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

