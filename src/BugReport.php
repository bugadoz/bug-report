<?php
namespace Bugadoz;

class BugReport
{
    private $apiKey;
    private $storagePath;

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
            return $filename;
        }

        return null;
    }
}
