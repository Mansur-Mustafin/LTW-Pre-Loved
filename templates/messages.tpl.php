<?php
declare(strict_types=1);

require_once(__DIR__ . '/../core/Chat.php');
require_once(__DIR__ . '/../utils/utils.php');
require_once(__DIR__ . '/../utils/Session.php');

$session = new Session();
?>

<?php function drawLoginMessage()
{ ?>
    <section class="message_middle">
        <h2>Make login into a system and send the message.</h2>
    </section>
<?php } ?>

<!-- Draw block of chats  -->
<?php function drawMessagesBlockChats(Session $session, array $chats, $current_user_id, $current_item_id): void
{ ?>
    <section id="chats_block">
        <h2>Messages</h2>
        <?php $last_message_id = 0;
        foreach ($chats as $chat) {
            if ($chat->last_message->id > $last_message_id){
                $last_message_id = $chat->last_message->id;
            }
            drawChat($session, $chat, $current_item_id);
        } ?>
        <input id="last_message_id" type="hidden" value="<?= $last_message_id ?>">
        <input id="current_user_id" type="hidden" value="<?= $current_user_id ?>">
        <input id="current_item_id" type="hidden" value="<?= $current_item_id ?>">
        <script src="../js/chats.js"></script>
    </section>
<?php } ?>


<!-- Draw each chat -->
<?php function drawChat(Session $session, Chat $chat, int $current_item_id): void
{ ?>
    <article class="chat fly">
        <a href="../pages/item.php?item_id=<?= $current_item_id ?>&chat_id=<?= $chat->id ?>">
            <figure class="profile_image">
                <img src="<?= htmlspecialchars($chat->chat_partner->image_path) ?>" 
                    alt="Profile image of <?= htmlspecialchars($chat->chat_partner->username) ?>">
                <figcaption><?= htmlspecialchars($chat->chat_partner->username) ?></figcaption>
            </figure>
            <?php if (!$chat->last_message->isRead && !$chat->last_message->isFromUserId($session->getId())) { ?>
                <div class="is_read"></div>
            <?php } ?>
            <p><?= htmlspecialchars(substr($chat->last_message->text, 0, 50)) . (strlen($chat->last_message->text) > 100 ? '...' : '') ?></p>
            <p class="chat-time-passed"><?= htmlspecialchars(getTimePassed($chat->last_message->date_time)) ?></p>
        </a>
    </article>
<?php } ?>


<!-- Draw block with messages -->
<?php function drawMessagesBlockMessages(Chat $chat, $current_user_id, $current_item, $solded = true): void
{ ?>
    <section id="messages_block">
        <a href="../pages/profile.php?id=<?=$chat->chat_partner->id?>" >
            <h2>Chat with: <span class='link'><?= $chat->chat_partner->username ?></span></h2>
        </a>

        <?php if ($chat->getChatPartnerId($current_user_id) != $current_item->user_id) { ?>
            <a href="../pages/item.php?item_id=<?= $current_item->id ?>">Back to chats</a>
        <?php } ?>
        
        <?php drawMessages($chat, $current_user_id); ?>
        
        <?php if (!$solded){ ?>
            <form action="../actions/action_send_message.php" method="post" class="message_form" id="message_form" data-chat_id="<?= $chat->id ?>"
                data-last_message_id="<?= $chat->getLastMessageId() ?>" enctype="multipart/form-data">

                <img src="../assets/img/file-plus.svg" alt="Add file" id="attach_file">
                <img src="../assets/img/exchange.svg" alt="Exchange button" id="offer_exchange">


                <label for="message_field"></label><textarea name="text" id="message_field" cols="50" rows="1" placeholder="Type here."></textarea>
                <input type="hidden" name="chat_id" value="<?= $chat->id ?>" id="chat_id_field">
                <input type="hidden" name="item_id" value="<?= $current_item->id ?>">
                <input type="hidden" name="offer_exchange" value="" id="offer_exchange_field">
                <input type="hidden" name="from_user_id" value="<?= $current_user_id ?>">
                <input id="attach_file_field" type="file" name="file" value="" style="display: none">
                <input type="hidden" name="to_user_id" value="<?= $chat->getChatPartnerId($current_user_id) ?>" id="to_user_id">

                <button type="submit">
                    <img src="../assets/img/send-message.svg" alt="Send message" id="offer_exchange">
                </button>
                
            </form>

            <p id="attached_file_name"></p>
        <?php } ?>
        

        <!-- TODO -->
        
        <article id="message_template" class="message my_message">
            <div>
                <p class="message_text text"></p>
                <p class="message_time"></p>
            </div>
        </article>
        

        <article id="message_template_partner" class="message partner_message">

            <figure class="profile_image">
                <img src="<?= $chat->chat_partner->image_path ?>" alt="Partner's profile image">
            </figure>

            <div>
                <p class="message_text text"></p>
                <p class="message_time"></p>
            </div>
        </article>

    </section>
    <script src="../js/messages.js"></script>
<?php } ?>


<?php function drawMessages(Chat $chat, int $current_user_id): void
{ ?>
    <section id="messages">
        <?php foreach ($chat->messages as $message) { ?>
            <article class="message <?= $message->isFromUserId($current_user_id) ? "my_message" : "partner_message" ?>">

                <?php if (!$message->isFromUserId($current_user_id)) { ?>
                    <figure class="profile_image">
                        <a href="../pages/profile.php?id=<?=$chat->chat_partner->id?>">
                            <img src="<?= $chat->chat_partner->image_path ?>" alt="Partner's profile image">
                        </a>
                    </figure>
                <?php } ?>

                <div>
                    <p class="message_text"><?= htmlentities($message->text) ?></p>

                    <?php if($message->item_id_exchange){ ?>
                        <br>
                        <div class="item_exchange_message">
                            <p>Offer for exchange:
                                <a href="/pages/item.php?item_id=<?= $message->item_id_exchange?>">
                                    <?= $message->item_for_exchange->title ?>
                                </a>
                            </p>
                            <img src="<?=$message->item_for_exchange->getImagesArray()[0]?>" alt="">
                        </div>
                    <?php } ?>

                    <?php if($message->filename != null){ ?>
                        <div class="attached_file">
                            <a target="_blank" href="../data/uploaded_files/<?= $message->getFullPath() ?>">
                                <?= htmlspecialchars(substr($message->filename, 64)) ?>
                            </a>
                            <?php if($message->isFileImage()){ ?>
                                <img src="../data/uploaded_files/<?= $message->getFullPath() ?>" alt="attached_file_image">
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <p class="message_time"><?= getTimePassed($message->date_time) ?></p>
                </div>

            </article>
        <?php } ?>
    </section>
<?php } ?>
