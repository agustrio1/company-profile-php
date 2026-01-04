<?php

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function send(): void
    {
        // Only set response code if headers not sent
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            
            foreach ($this->headers as $key => $value) {
                header("$key: $value");
            }
        }
        
        echo $this->content;
    }

    public function json(mixed $data, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
        
        return $this;
    }

    public function redirect(string $url, int $statusCode = 302): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header("Location: $url");
        }
        exit;
    }

    public function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    public function with(string $key, mixed $value): self
    {
        $_SESSION['_flash'][$key] = $value;
        return $this;
    }

    public function withInput(): self
    {
        $_SESSION['_old'] = $_POST;
        return $this;
    }

    public function withErrors(array $errors): self
    {
        $_SESSION['_errors'] = $errors;
        return $this;
    }

    // FIX: Explicitly mark parameter as nullable
    public function download(string $filePath, ?string $fileName = null): void
    {
        if (!file_exists($filePath)) {
            if (!headers_sent()) {
                http_response_code(404);
            }
            exit('File not found');
        }

        $fileName = $fileName ?? basename($filePath);
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath);

        if (!headers_sent()) {
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . $fileSize);
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        }

        readfile($filePath);
        exit;
    }

    public function view(string $view, array $data = []): self
    {
        $viewContent = View::render($view, $data);
        $this->setContent($viewContent);
        
        return $this;
    }

    public static function make(): self
    {
        return new self();
    }
}