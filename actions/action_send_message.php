<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/message.db.php');

$db = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['chat_id']) ||
        empty($_POST['from_user_id']) ||
        (empty($_POST['text']) && empty($_POST['offer_exchange'])) ||
        empty($_POST['to_user_id']) ||
        empty($_POST['item_id'])) {
        http_response_code(400);
    }

    $chat_id = intval($_POST['chat_id']);
    $from_user_id = intval($_POST['from_user_id']);
    $text = $_POST['text'];
    $to_user_id = intval($_POST['to_user_id']);
    $item_id = intval($_POST['item_id']);
    $offer_exchange = intval($_POST['offer_exchange']);



    $message_info = addMessage($db, $chat_id, $from_user_id, $to_user_id, $text, $item_id, $offer_exchange);
    print(json_encode($message_info));
    http_response_code(200);

}