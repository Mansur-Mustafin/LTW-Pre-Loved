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
    if (!isset($data['user_id']) ||
        !isset($data['item_id']) ||
        !isset($data['last_message_id'])) {
        http_response_code(400);
    }

    $user_id = intval($data['user_id']);
    $last_message_id = intval($data['last_message_id']);
    $item_id = intval($data['item_id']);

    $messages = getNewMessagesByUserIdAndMessageId($db, $user_id, $last_message_id, $item_id);

    print json_encode($messages);
}