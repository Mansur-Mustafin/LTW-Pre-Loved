<?php
  class Session {
    private array $messages;

    public function __construct() {
      session_start();

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function getId() : ?int {
      return isset($_SESSION['id']) ? $_SESSION['id'] : null;    
    }

    public function getName() : ?string {
      return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public function setId(int $id) {
      $_SESSION['id'] = $id;
    }

    public function setName(string $name) {
      $_SESSION['name'] = $name;
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }

    public function getErrorMessages(): array {
      $errorMessages = [];
      foreach ($this->messages as $message) {
          if ($message['type'] === 'error') {
              $errorMessages[] = $message['text'];
          }
      }
      $this->clearMessagesOfType('error');
      return $errorMessages;
    }

    public function getSuccesMessages(): array {
      $errorMessages = [];
      foreach ($this->messages as $message) {
          if ($message['type'] === 'success') {
              $errorMessages[] = $message['text'];
          }
      }
      $this->clearMessagesOfType('success');
      return $errorMessages;
    }
    
    public function clearMessagesOfType(string $type): void {
      // Filter out the error messages from $this->messages
      $this->messages = array_filter($this->messages, function ($message) {
          return $message['type'] !== $type;
      });

      // Clear error messages from the session
      if (isset($_SESSION['messages'])) {
          $_SESSION['messages'] = array_filter($_SESSION['messages'], function ($message) {
              return $message['type'] !== $type;
          });
      }
    }
  }
?>