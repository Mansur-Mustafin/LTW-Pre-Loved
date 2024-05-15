<?php

class Session 
{
    private array $messages;

    public function __construct()
    {
        session_start();

        $this->messages = $_SESSION['messages'] ?? array();
        unset($_SESSION['messages']);

        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = Session::generate_random_token();
        }
    }

    private static function generate_random_token(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function isSameCsrf(?string $csrf) : bool
    {
        $isMatch = isset($csrf) && $csrf === $_SESSION['csrf'];
        $_SESSION['csrf'] = Session::generate_random_token();
        return $isMatch;
    }

    public function isLoggedIn() : bool
    {
        return isset($_SESSION['id']);
    }

    public function isAdmin(): bool
    {
        return isset($_SESSION['admin']) && ($_SESSION['admin'] == true);
    }

    public function logout() : void
    {
        session_destroy();
    }

    public function getId() : ?int
    {
        return $_SESSION['id'] ?? null;
    }

    public function getName() : ?string
    {
        return $_SESSION['name'] ?? null;
    }

    public function setId(int $id): void
    {
        $_SESSION['id'] = $id;
    }

    public function setName(string $name): void
    {
        $_SESSION['name'] = $name;
    }

    public function setAdmin(bool $admin): void
    {
        $_SESSION['admin'] = $admin;
    }

    public function addMessage(string $type, string $text): void
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
            if ($message['type'] === 'error') {
                $errorMessages[] = $message['text'];
            }
        }
        $this->clearMessagesOfType('error');
        return $errorMessages;
    }

    public function getSuccesMessages(): array
    {
        $errorMessages = [];
        foreach ($this->messages as $message) {
            if ($message['type'] === 'success') {
                $errorMessages[] = $message['text'];
            }
        }
        $this->clearMessagesOfType('success');
        return $errorMessages;
    }
  
    public function clearMessagesOfType(string $type): void
    {
        $this->messages = array_filter($this->messages, function ($message) use ($type) {
            return $message['type'] !== $type;
        });

        if (isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array_filter($_SESSION['messages'], function ($message) use ($type) {
                return $message['type'] !== $type;
            });
        }
    }
}
