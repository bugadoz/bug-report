<?php
namespace Bugadoz;

class BugReport
{
    private $apiKey;
    private $storagePath;
    private $apiEndpoint = 'https://bugadoz.dev/report';

    public function __construct(string $apiKey, string $storagePath)
    {
        $this->apiKey = $apiKey;
        $this->storagePath = rtrim($storagePath, '/') . '/';
    }

    public function getUploadScriptPath(): string
    {
        return $this->storagePath . 'bug_upload.php';
    }

    public function saveFile(array $file): ?string
    {
        if (!isset($file['tmp_name'])) {
            return null;
        }

        $filename = uniqid('bug_') . '_' . basename($file['name']);
        $destination = $this->storagePath . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        }

        return null;
    }

    /**
     * Gera a URL atual completa (protocolo + domínio + URI)
     */
    private function getCurrentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
            ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Envia os dados do bug para a API central
     * 
     * @param array $dados Ex: ['descricao' => '', 'url' => '', 'navegador' => '', 'sistema' => '']
     * @param string|null $caminhoArquivo Caminho para imagem ou vídeo salvo
     * @return string|false Resposta da API
     */
    public function reportBug(array $dados, ?string $caminhoArquivo = null)
    {
        $post = [
            'api_key'   => $this->apiKey,
            'descricao' => $dados['descricao'] ?? '',
            'url'       => $dados['url'] ?? $this->getCurrentUrl(), // <- Aqui pegamos a URL atual se não for fornecida
            'navegador' => $dados['navegador'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '',
            'sistema'   => $dados['sistema'] ?? PHP_OS,
        ];

        // Se houver arquivo, inclui no POST
        if ($caminhoArquivo && file_exists($caminhoArquivo)) {
            $post['arquivo'] = new \CURLFile($caminhoArquivo);
        }

        $ch = curl_init($this->apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resposta = curl_exec($ch);

        if (curl_errno($ch)) {
            return false;
        }

        curl_close($ch);
        return $resposta;
    }
}
