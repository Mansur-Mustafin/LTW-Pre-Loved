<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/user.class.php');
require_once(__DIR__ . '/../core/message.class.php');
require_once(__DIR__ . '/../database/user.db.php');

class Chat 
{
    public int $id;
    public int $item_id;
    public int $from_user_id;
    public int $to_user_id;
    public array $messages = [];
    public User $chat_partner;
    public Message $last_message;

    public function __construct(
        int $id,
        int $item_id,
        int $from_user_id,
        int $to_user_id
    ) {
        $this->id = $id;
        $this->from_user_id = $from_user_id;
        $this->to_user_id = $to_user_id;
        $this->item_id = $item_id;
    }

    public static function newChat( $from_user_id, $to_user_id, $item_id ) 
    {
        return new Chat(
            0,
            $item_id,
            $from_user_id,
            $to_user_id
        );
    }

    public function getChatPartnerId(int $current_user_id)
    {
        if($current_user_id == $this->to_user_id){
            return $this->from_user_id;
        }
        return $this->to_user_id;
    }

    public function getLastMessageId()
    {
        if(sizeof($this->messages)){
            return $this->messages[sizeof($this->messages)-1]->id;
        }
        return null;
    }

}