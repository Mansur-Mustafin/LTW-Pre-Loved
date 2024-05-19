<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/Chat.php');
require_once(__DIR__ . '/../core/Message.php');
require_once(__DIR__ . '/../database/message.db.php');

function getChatsByUserItem(PDO $db, int $user_id, int $item_id): array
{
    $sql = "SELECT Chats.*, MAX(Messages.id) AS last_message_id
            FROM Chats
            JOIN Messages ON Chats.id = Messages.chat_id
            WHERE Chats.item_id = :item_id 
                AND (Chats.from_user_id = :user_id OR Chats.to_user_id = :user_id)
            GROUP BY Chats.id;";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

    $stmt->execute();

    $chats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $chat = new Chat(
            id: $row['id'],
            item_id: $row['item_id'],
            from_user_id: $row['from_user_id'],
            to_user_id: $row['to_user_id'],
        );
        $chat->last_message = getMessagesById($db, $row['last_message_id']);
        $chat->chat_partner = getUserById($db, $chat->getChatPartnerId($user_id));
        $chats[] = $chat;
    }

    return $chats;
}

function getChatById(PDO $db, int $chat_id, int $user_id): ?Chat
{
    $sql = "SELECT Chats.*
            FROM Chats 
            WHERE id = :chat_id;";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row) {
        $chat = new Chat(
            id: $row['id'],
            item_id: $row['item_id'],
            from_user_id: $row['from_user_id'],
            to_user_id: $row['to_user_id'],
        );

        $chat->chat_partner = getUserById($db, $chat->getChatPartnerId($user_id));
        $chat->messages = getMessagesByChatId($db, $chat_id);

        return $chat;
    }
    return null;
}

function addChat(PDO $db, int $item_id, int $from_user_id, int $to_user_id): int
{
    if ($from_user_id > $to_user_id) {
        $temp = $from_user_id;
        $from_user_id = $to_user_id;
        $to_user_id = $temp;
    }

    $sql = "INSERT INTO Chats (item_id, from_user_id, to_user_id)
        VALUES (:item_id, :from_user_id, :to_user_id)";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':from_user_id', $from_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':to_user_id', $to_user_id, PDO::PARAM_INT);

    $stmt->execute();

    return intval($db->lastInsertId());
}
