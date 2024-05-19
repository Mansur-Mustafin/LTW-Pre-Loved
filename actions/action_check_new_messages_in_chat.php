<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/message.db.php');

$db = getDatabaseConnection();

if ($request->isPost()) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['chat_id']) ||
        !isset($data['last_message_id']) ||
        !isset($data['to_user_id'])) {
        http_response_code(400);
    }

    $chat_id = intval($data['chat_id']);
    $last_message_id = intval($data['last_message_id']);
    $to_user_id = intval($data['to_user_id']);

    $messages = getNewMessagesByChatIdAndMessageId($db, $chat_id, $last_message_id, $to_user_id);

    print json_encode($messages);
}
