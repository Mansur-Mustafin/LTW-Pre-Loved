<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/Request.php');
require_once(__DIR__ . '/../utils/Validate.php');

$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/transaction.db.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$request = new Request();

if (!$request->validateCsrfToken()) {
    throw new Exception('Bad Request', 400);
}

$userId = $session->getId();

$items_in_cart = itemsInCart($db, $session->getId());
$items_in_wishlist = itemsInWishlist($db, $session->getId());

$items = getAllItemsFromId($db, $items_in_cart);

$validate = Validate::in($request->getPostParams())
    ->required(['cardType'])
    ->inList(['cardType'], ['visa', 'mbWay']);

if ($request->post('cardType') == 'visa') {
    $validate
        ->required(['cardNumber', 'cvv'])
        ->int(['cardNumber', 'cvv'])
        ->length(['cardNumber'], 12, 12)
        ->length(['cvv'], 3, 3);

} elseif ($request->post('cardType') == 'mbWay') {
    $validate
        ->required(['phone'])
        ->int(['phone']);
}

if (!empty($validate->getErrors())) {
    foreach ($validate->getErrors() as $attrErrors) {
        foreach ($attrErrors as $error) {
            $session->addMessage(Session::ERROR_TYPE, $error);
        }
    }

    header("Location: {$request->getReferer()}");
    return;
}


completeCheckout($db, $userId, $items);

header('Location: /pages/profile.php?action=transactions');
