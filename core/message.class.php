<?php

declare(strict_types=1);

class Message {
    public int $id;
    public int $date_time;
    public string $text;
    public int $chat_id;
    public int $from_user_id;  // TODO
    public int $to_user_id;    // TODO
    public bool $isRead;
    public ?int $item_id_exchange;
    public Item $item_for_exchange;

    public function __construct(int $id, 
                                ?int $date_time = null, 
                                string $text, 
                                int $chat_id, 
                                int $from_user_id, 
                                int $to_user_id, 
                                bool $read,
                                ?int $item_id_exchange=null)
    {
        $this->id = $id;
        $this->date_time = $date_time ?? time();
        $this->text = $text;
        $this->chat_id = $chat_id;
        $this->from_user_id = $from_user_id;
        $this->to_user_id = $to_user_id;
        $this->isRead = $read;
        $this->item_id_exchange = $item_id_exchange;
    }

    public function isFromUserId(int $from_user_id){
        return $this->from_user_id == $from_user_id;
    }


}