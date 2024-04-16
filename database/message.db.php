<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/chat.class.php');
require_once(__DIR__ . '/../core/message.class.php');


function getMessagesById(PDO $db, int $message_id): Message
{
    $sql = "SELECT * FROM Messages WHERE Messages.id = :message_id;";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

    $stmt->execute();

    $messages = [];
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $message = new Message(
        $row['id'],
        $row['date_time'],
        $row['text'],
        $row['chat_id'],
        $row['from_user_id'],
        $row['to_user_id'],
        (bool)$row['is_read']
    );


    return $message;
}


function getMessagesByChatId(PDO $db, int $chat_id): array
{
    $sql = "SELECT * FROM Messages WHERE Messages.chat_id = :chat_id;";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);

    $stmt->execute();

    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $message = new Message(
            id: $row['id'],
            date_time: $row['date_time'],
            text: $row['text'],
            chat_id: $row['chat_id'],
            from_user_id: $row['from_user_id'],
            to_user_id: $row['to_user_id'],
            read: (bool)$row['is_read']
        );
        $messages[] = $message;
    }

    return $messages;
}
