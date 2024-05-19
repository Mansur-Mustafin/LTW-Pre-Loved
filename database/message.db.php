<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/Chat.php');
require_once(__DIR__ . '/../core/Message.php');
require_once(__DIR__ . '/../database/chat.db.php');
require_once(__DIR__ . '/../database/item.db.php');

function getMessagesById(PDO $db, int $message_id): Message
{
    $sql = "SELECT * FROM Messages WHERE Messages.id = :message_id;";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':message_id', $message_id, PDO::PARAM_INT);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $message = new Message(
        id: $row['id'],
        text: $row['text'],
        chat_id: $row['chat_id'],
        from_user_id: $row['from_user_id'],
        to_user_id: $row['to_user_id'],
        isRead: (bool)$row['is_read'],
        item_id_exchange: $row['item_id_exchange'],
        filename: $row['files'],
        date_time: $row['date_time'],
    );
    if($row['item_id_exchange'] != 0){
        $message->item_for_exchange = getItem($row['item_id_exchange']);
    }

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
            text: $row['text'],
            chat_id: $row['chat_id'],
            from_user_id: $row['from_user_id'],
            to_user_id: $row['to_user_id'],
            isRead: (bool)$row['is_read'],
            item_id_exchange: $row['item_id_exchange'],
            filename: $row['files'],
            date_time: $row['date_time'],
        );
        if($row['item_id_exchange'] != 0){
            $message->item_for_exchange = getItem($row['item_id_exchange']);
        }
        $messages[] = $message;
    }

    return $messages;
}

function addMessage($db, int $chat_id, int $from_user_id, int $to_user_id, string $text, int $item_id=0, int $offer_exchange=0, string $filename = ""): array
{
    $chat = getChatById($db, $chat_id, $from_user_id);
    if($chat === null) {
        $chat_id = addChat($db, $item_id, $from_user_id, $to_user_id);
    }

    $sql = "INSERT INTO Messages (chat_id, from_user_id, to_user_id, text, item_id_exchange, files, date_time)
        VALUES (:chat_id, :from_user_id, :to_user_id, :text, :item_id_exchange, :files, :date_time)";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
    $stmt->bindParam(':from_user_id', $from_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':to_user_id', $to_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $offer_exchange = ($offer_exchange != 0) ? $offer_exchange : null;
    $stmt->bindParam(':item_id_exchange', $offer_exchange, PDO::PARAM_INT);
    $filename = $filename ?? null;
    $stmt->bindParam(':files', $filename, PDO::PARAM_STR);
    $stmt->bindParam(':date_time', time(), PDO::PARAM_INT);

    $stmt->execute();

    return [
        "id" => intval($db->lastInsertId()),
        "chat_id" => $chat_id
    ];
}

function getNewMessagesByChatIdAndMessageId(PDO $db, int $chat_id, int $message_id, int $to_user_id): array 
{
    $sql = "UPDATE Messages SET is_read = 1 WHERE 
                    Messages.chat_id = :chat_id AND
                    Messages.from_user_id = :to_user_id;";

    $stmt = $db->prepare($sql);

    $stmt->execute(['chat_id' => $chat_id, 'to_user_id' => $to_user_id]);

    $sql = "SELECT * FROM Messages WHERE 
            Messages.chat_id = :chat_id AND
            Messages.id > :message_id;";

    $stmt = $db->prepare($sql);

    $stmt->execute(['chat_id' => $chat_id, 'message_id' => $message_id]);

    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = $row;
    }

    return $messages;
}

function getNewMessagesByUserIdAndMessageId(PDO $db, int $user_id, int $message_id, int $item_id): array 
{
    $sql = "SELECT Messages.*, Chats.item_id as item_id 
            FROM Messages 
            JOIN Chats ON Messages.chat_id = Chats.id
            WHERE Messages.to_user_id = :user_id AND
                Messages.id > :message_id AND 
                item_id = :item_id;";

    $stmt = $db->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'message_id' => $message_id, 'item_id' => $item_id]);

    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = $row;
    }

    return $messages;
}
