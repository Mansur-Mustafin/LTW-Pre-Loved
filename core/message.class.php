<?php

declare(strict_types=1);

class Message 
{
    public function __construct(
        public int $id,
        public string $text,
        public int $chat_id,
        public int $from_user_id,
        public int $to_user_id,
        public bool $isRead,
        public ?int $item_id_exchange = null,
        public ?Item $item_for_exchange = null,
        public ?string $filename = null,
        public ?int $date_time = null
    ) {
        $this->date_time = $date_time ?? time();
    }

    public function isFromUserId(int $from_user_id) : bool
    {
        return $this->from_user_id == $from_user_id;
    }

    public function isFileImage() : bool
    {
        return (bool)exif_imagetype("../data/uploaded_files/".$this->getFullPath());
    }

    public function getFullPath() : string
    {
        return substr($this->filename, 0, 3).'/'.$this->filename;
    }
}
