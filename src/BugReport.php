<?php

namespace Bugadoz;

class BugReport
{
    /**
     * @var string Chave de autenticação do usuário
     */
    private string $apiKey;

    /**
     * @var string Visibilidade do log: public, private ou test
     */
    private string $privacy;

    /**
     * @var string URL da API do bugadoz.dev
     */
    private string $apiEndpoint = 'https://bugadoz.dev/report';

    /**
     * Construtor da classe
     * 
     * @param string $apiKey Chave de autenticação
     * @param string|null $privacy Visibilidade do log: public, private ou test (default: public)
     */
    public function __construct(string $apiKey, ?string $privacy = 'public')
    {
        $this->apiKey = $apiKey;
        $this->privacy = in_array($privacy, ['public', 'private', 'test']) ? $privacy : 'public';
    }

    /**
     * Gera a URL atual completa (protocolo + domínio + URI)
     * 
     * @return string URL atual
     */
    private function getCurrentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
            ? 'https://' : 'http://';

        return $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');
    }

    /**
     * Envia os dados do bug para a API do bugadoz.dev
     * 
     * @param array $dados Informações do bug: descricao, url, navegador, sistema
     * @return string|false Resposta da API ou false em caso de erro
     */
    public function reportBug(array $dados)
    {
        // Monta o corpo do POST com dados e preenchimentos padrão
        $post = [
            'api_key'   => $this->apiKey,
            'descricao' => $dados['descricao'] ?? 'Sem descrição',
            'url'       => $dados['url'] ?? $this->getCurrentUrl(),
            'navegador' => $dados['navegador'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido'),
            'sistema'   => $dados['sistema'] ?? PHP_OS,
            'privacy'   => $this->privacy,
        ];

        // Inicializa cURL
        $ch = curl_init($this->apiEndpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $resposta = curl_exec($ch);

        // Verifica erros de cURL
        if (curl_errno($ch)) {
            curl_close($ch);
            error_log('Erro ao enviar bug: ' . curl_error($ch));
            return false;
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verifica resposta HTTP
        if ($statusCode !== 200) {
            error_log("Falha ao enviar log. Código HTTP: $statusCode");
            return false;
        }

        return $resposta;
    }
}
