<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));


require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/message.db.php');
require_once(__DIR__.'/../utils/hash.php');
require_once(__DIR__.'/../utils/utils.php');

$db = getDatabaseConnection();

if ($request->isPost()) {

    if (empty($request->post('chat_id')) ||
        empty($request->post('from_user_id')) ||
        (empty($request->post('text')) && empty($request->post('offer_exchange'))) ||
        empty($request->post('to_user_id')) ||
        empty($request->post('item_id'))) {
        http_response_code(400);
    }

    $chat_id = intval($request->post('chat_id'));
    $from_user_id = intval($request->post('from_user_id'));
    $text = $request->post('text');
    $to_user_id = intval($request->post('to_user_id'));
    $item_id = intval($request->post('item_id'));
    $offer_exchange = intval($request->post('offer_exchange'));
    $filename = "";
    if($_FILES['file']['size'] != 0){
        $filename = get_hash_path((string) time()).$_FILES['file']['name'];
        $folderHash = substr($filename, 0, 3);
        try {
            ensureFolderExists(__DIR__ . "/../data/uploaded_files/$folderHash/");
        } catch (Exception $e) {
        }

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
