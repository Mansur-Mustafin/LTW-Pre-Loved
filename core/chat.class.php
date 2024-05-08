<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/user.class.php');
require_once(__DIR__ . '/../core/message.class.php');
require_once(__DIR__ . '/../database/user.db.php');

class Chat 
{
    public function __construct(
        public int $item_id,
        public int $from_user_id,
        public int $to_user_id,
        public int $id = 0,
        public array $messages = [],
        public ?User $chat_partner = null,
        public ?Message $last_message = null,
    ) {}

    public static function newChat( $from_user_id, $to_user_id, $item_id ): self
    {
        return new Chat(
            0,
            $item_id,
            $from_user_id,
            $to_user_id
        );
    }

    public function getChatPartnerId(int $current_user_id): int
    {
        if($current_user_id == $this->to_user_id){
            return $this->from_user_id;
        }
        return $this->to_user_id;
    }

    public function getLastMessageId(): ?int
    {
        return !empty($this->messages) ? end($this->messages)->id : null;
    }
}
