<?php

declare(strict_types=1);

class Request
{
    public function get($key, $default = null): ?string
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    public function post($key, $default = null): ?string
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public function getGetParams(): array
    {
        return $this->sanitizeArray($_GET);
    }

    public function getPostParams(): array
    {
        return $this->sanitizeArray($_POST);
    }

    private function sanitize($value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    private function sanitizeArray($array): array
    {
        $sanitizedArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitizedArray[$key] = $this->sanitizeArray($value);
            } else {
                $sanitizedArray[$key] = $this->sanitize($value);
            }
        }

        return $sanitizedArray;
    }

    public function getReferer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    public function validateCsrfToken(): bool
    {
        if (!$this->isPost() || $this->post('csrf_token') === null) {
            return false;
        }

        $token = $this->post('csrf_token');

        static::checkSession();

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            $this->clearCsrfToken();

            return true;
        }

        return false;
    }

    public static function generateCsrfTokenInput(): string
    {
        static::checkSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $token = $_SESSION['csrf_token'];

        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    private function clearCsrfToken(): void
    {
        static::checkSession();

        unset($_SESSION['csrf_token']);
        unset($_POST['csrf_token']);
    }

    private static function checkSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}
