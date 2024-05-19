<?php

class Session
{
    public const ERROR_TYPE = 'error';
    public const SUCCESS_TYPE = 'success';

    private array $messages;

    public function __construct(bool $initDefaultSessionParams = true)
    {
        self::checkSession();

        if ($initDefaultSessionParams) {
            self::initDefaultSessionParams();
        }

        $this->messages = $_SESSION['messages'] ?? array();
        unset($_SESSION['messages']);
    }

    public static function initDefaultSessionParams(): void
    {
        Session::checkSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function checkSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['id']);
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['admin']) && ($_SESSION['admin'] == true);
    }

    public function logout()
    {
        session_destroy();
    }

    public function getId(): ?int
    {
        return $_SESSION['id'] ?? null;
    }

    public function getName(): ?string
    {
        return $_SESSION['name'] ?? null;
    }

    public function getCsrfToken(): ?string
    {
        return $_SESSION['csrf_token'];
    }

    public function setId(int $id)
    {
        $_SESSION['id'] = $id;
    }

    public function setName(string $name)
    {
        $_SESSION['name'] = $name;
    }

    public function setAdmin(bool $admin)
    {
        $_SESSION['admin'] = $admin;
    }

    public function addMessage(string $type, string $text)
    {
        $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getErrorMessages(): array
    {
        $errorMessages = [];

        foreach ($this->messages as $message) {
            if ($message['type'] === self::ERROR_TYPE) {
                $errorMessages[] = $message['text'];
            }
        }

        $this->clearMessagesOfType('error');

        return $errorMessages;
    }

    public function getSuccessMessages(): array
    {
        $errorMessages = [];

        foreach ($this->messages as $message) {
            if ($message['type'] === self::SUCCESS_TYPE) {
                $errorMessages[] = $message['text'];
            }
        }

        $this->clearMessagesOfType('success');

        return $errorMessages;
    }

    public function clearMessagesOfType(string $type): void
    {
        // Filter out the error messages from $this->messages
        $this->messages = array_filter($this->messages, function ($message) use ($type) {
            return $message['type'] !== $type;
        });

        // Clear error messages from the session
        if (isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array_filter($_SESSION['messages'], function ($message) use ($type) {
                return $message['type'] !== $type;
            });
        }
    }
}