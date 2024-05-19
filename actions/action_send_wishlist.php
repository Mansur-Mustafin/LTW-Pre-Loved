<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

$session = new Session();
$request = new Request();

if(!$session->isLoggedIn()) die(header('Location: /'));


require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__.'/../utils/validation.php');
require_once(__DIR__ . '/../utils/Validate.php');

$db = getDatabaseConnection();

if ($request->isPost()) {
    $email = $request->post('email');
    $email = 'mustafin.mansur02@gmail.com'; // TODO: change this email.
    $message = $request->post('wishlist-message');

    $validator = Validate::in($request->getPostParams())
        ->required(['email'])  
        ->match('email', '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/');  // <mansur>@<gmail>.<com>

    if ($errors = $validator->getErrors()) {
        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $session->addMessage('error', $message);
            }
        }
        header('Location: ../pages/login.php');
        die();
    }
    
    $items_in_wishlist = itemsInWishlist($session->getId()); // TODO or better set all ids as a <input type=hidden>?

    if(empty($items_in_wishlist)){
        $session->addMessage('error', 'Wishlist is empty');
        die(header('Location: ../pages/wishlist.php'));
    }

    $emailContent = "Here's my wishlist:\n\n";
    foreach ($items_in_wishlist as $id) {
        $emailContent .= "http://localhost:9000/pages/item.php?item_id=$id" . "\n";
    }

    if (!empty($message)) {
        $emailContent .= "\nPersonal Message:\n" . $message;
    }

    $headers = "From: mans.mustafin@gmail.com\r\n";
    mail($email, "Wishlist from " . $session->getName(), $emailContent, $headers);

    $session->addMessage('success', 'Email was sended successfully!');
}

header('Location: ../pages/wishlist.php');
exit;