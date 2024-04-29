<?php

declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php');

$session = new Session();

if(!$session->isLoggedIn()) {
    die(header('Location: /'));
}

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/message.db.php');
require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/utils.php');

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
    $filename = "";
    if($_FILES['file']['size'] != 0){
        $filename = get_hash_path((string) time()).$_FILES['file']['name'];
        $folderHash = substr($filename, 0, 3);
        ensureFolderExists(__DIR__."/../data/uploaded_files/$folderHash/");

        $save_path = '/data/uploaded_files/'.htmlspecialchars($folderHash.'/'.$filename);

        move_uploaded_file( $_FILES['file']['tmp_name'], __DIR__.'/..'.$save_path);
    }

    $message_info = addMessage($db, $chat_id, $from_user_id, $to_user_id, $text, $item_id, $offer_exchange, $filename);

    if($_FILES['file']['size'] != 0){
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    print(json_encode($message_info));
    http_response_code(200);
}
