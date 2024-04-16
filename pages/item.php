<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();


require_once(__DIR__ . '/../database/item.db.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/chat.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/filter.tpl.php');
require_once(__DIR__ . '/../templates/item.tpl.php');
require_once(__DIR__ . '/../templates/messages.tpl.php');

$db = getDatabaseConnection();

$item_id = intval($_GET['item_id']);
$item = getItem($db, $item_id);
$chats = null;
$chat = null;
$isCustomer = false;

if(!isset($_GET['chat_id']) && $session->isLoggedIn()) {
    $chats = getChatsByUserItem($db, $session->getId(), $item_id);
    if(sizeof($chats) == 0 && $item->user_id != $session->getId()){     // buyer and no messages.
        $chat = Chat::newChat($session->getId(), $item->user_id, $item_id);
        $chat->chat_partner = getUserById($db, $chat->getChatPartnerId($session->getId()));
    } else if($item->user_id != $session->getId()){                     // buyer and there are messages
        $chat = $chats[0];
        header("Location: /pages/item.php?item_id=$item_id&chat_id=".$chat->getId());
        $isCustomer = true;
    }
} else if($session->isLoggedIn()){
    $chat_id = intval($_GET['chat_id']);                                // opened chat
    $chat = getChatById($db, $chat_id, $session->getId());
}

$in_cart = false;
$in_wishlist = false;

if($session->isLoggedIn() && $session->getId() != $item->user_id){
    $items_in_cart = itemsInCart($db, $session->getId());
    $items_in_wishlist = itemsInWishlist($db, $session->getId());

    $in_cart = in_array($item->id, $items_in_cart);
    $in_wishlist = in_array($item->id, $items_in_wishlist);
}

drawHeader($session, $item->title);
drawItemMain($item, $session, $in_cart, $in_wishlist);

if($chat !== null || $isCustomer)
    drawMessagesBlockMessages($chat, $session->getId(), $item);
else if($chats !== null)
    drawMessagesBlockChats($chats, $session->getId(), $item_id);
else if(!$session->isLoggedIn()){
    drawLoginMessage();
}

drawFooter();

