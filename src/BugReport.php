<?php

namespace Bugadoz;

class BugReport
{
    private string $apiKey;
    private string $privacy;
    private string $apiEndpoint = 'https://bugadoz.dev/report';

    public function __construct(string $apiKey, ?string $privacy = 'public')
    {
        $this->apiKey = $apiKey;
        $this->privacy = in_array($privacy, ['public', 'private', 'test']) ? $privacy : 'public';
    }

    private function getCurrentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
            ? 'https://' : 'http://';

        return $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
    }

    public function reportBug(array $dados)
    {
        $post = [
            'api_key'   => $this->apiKey,
            'descricao' => $dados['descricao'] ?? 'Sem descrição',
            'url'       => $dados['url'] ?? $this->getCurrentUrl(),
            'navegador' => $dados['navegador'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido'),
            'sistema'   => $dados['sistema'] ?? PHP_OS,
            'privacy'   => $this->privacy,
        ];

        $ch = curl_init($this->apiEndpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $resposta = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            error_log('Erro ao enviar bug: ' . curl_error($ch));
            return false;
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode !== 200) {
            error_log("Falha ao enviar log. Código HTTP: $statusCode");
            return false;
        }

        return $resposta;
    }

    public function inicializarCapturaAutomatica()
    {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        error_reporting(E_ALL);

        $reportador = $this;

        $logger = function ($mensagem) use ($reportador) {
            $data = date('Y-m-d H:i:s');
            $timestamp = date('Y-m-d_H-i-s');
            $arquivo = "erros/erro_$timestamp.txt";

            if (!file_exists('erros')) {
                mkdir('erros', 0777, true);
            }

            $extras = "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'CLI') . "\n";
            $extras .= "Navegador: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido') . "\n";
            $extras .= "URL: " . ($_SERVER['REQUEST_URI'] ?? 'CLI') . "\n";

            $conteudo = "[$data]\n$mensagem\n$extras\n";

            file_put_contents($arquivo, $conteudo, FILE_APPEND);

            $reportador->reportBug([
                'descricao' => $mensagem,
                'url' => $reportador->getCurrentUrl(),
                'navegador' => $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido',
                'sistema' => PHP_OS,
            ]);
        };

        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($logger) {
            $mensagem = "Erro [$errno]: $errstr em $errfile na linha $errline";
            $logger($mensagem);
        });

        set_exception_handler(function ($exception) use ($logger) {
            $mensagem = "Exceção: " . $exception->getMessage() .
                        " em " . $exception->getFile() .
                        " na linha " . $exception->getLine();
            $logger($mensagem);
        });

        register_shutdown_function(function () use ($logger) {
            $erro = error_get_last();
            if ($erro && in_array($erro['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $mensagem = "Erro fatal: {$erro['message']} em {$erro['file']} na linha {$erro['line']}";
                $logger($mensagem);
            }
        });
    }
}
