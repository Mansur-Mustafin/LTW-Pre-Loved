<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');

class Request
{
    public function __construct($enableCsrfValidation = true)
    {
        if ($enableCsrfValidation) self::init();
    }

    public static function init(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !self::validateCsrfToken()) {
            throw new Exception('Bad Request', 400);
        }
    }

    public function get($key, $default = null): ?string
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }

    public function post($key, $default = null): ?string
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isGet(): bool
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
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    public static function validateCsrfToken(): bool
    {
        if (!$_SERVER['REQUEST_METHOD'] || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
            return false;
        }

        $token = $_POST['csrf_token'];

        Session::checkSession();

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            self::clearCsrfToken();

            return true;
        }

        return false;
    }

    public static function generateCsrfTokenInput(): string
    {
        // Session::checkSession();
        Session::initDefaultSessionParams();

        return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }

    private static function clearCsrfToken(): void
    {
        Session::checkSession();

        unset($_SESSION['csrf_token']);
        unset($_POST['csrf_token']);
    }
}
