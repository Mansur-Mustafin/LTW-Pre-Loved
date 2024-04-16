<?php
declare(strict_types=1);

require_once(__DIR__ . '/../core/chat.class.php');
require_once(__DIR__ . '/../utils/utils.php');
?>

<?php function drawLoginMessage(){ ?>
    <section id="messages_block_no_login">
        <h2>Make login into a system and send the message.</h2>
    </section>
<?php } ?>

<!-- Draw block of chats  -->
<?php function drawMessagesBlockChats(array $chats, $current_user_id, $current_item_id): void { ?>
    <section id="chats_block">
        <h2>Messages</h2>
        <?php $last_message_id = 0;
        foreach ($chats as $chat) {
            if ($chat->last_message->id > $last_message_id){
                $last_message_id = $chat->last_message->id;
            }
            drawChat($chat, $current_item_id);
        } ?>
        <input id="last_message_id" type="hidden" value="<?= $last_message_id ?>">
        <input id="current_user_id" type="hidden" value="<?= $current_user_id ?>">
        <script src="../js/chats.js"></script>
    </section>
<?php } ?>


<!-- Draw each chat -->
<?php function drawChat(Chat $chat, int $current_item_id): void { ?>
    <article class="chat fly">
        <a href="../pages/item.php?item_id=<?= $current_item_id ?>&chat_id=<?= $chat->id ?>">
            <figure class="profile_image">
                <img src="<?= htmlspecialchars($chat->chat_partner->image_path) ?>" 
                    alt="Profile image of <?= htmlspecialchars($chat->chat_partner->username) ?>">
                <figcaption><?= htmlspecialchars($chat->chat_partner->username) ?></figcaption>
            </figure>
            <?php if (!$chat->last_message->isRead) { ?>
                <div class="is_read"></div>
            <?php } ?>
            <p><?= htmlspecialchars($chat->last_message->text) ?></p>
            <p class="chat-time-passed"><?= htmlspecialchars(getTimePassed($chat->last_message->date_time)) ?></p>
        </a>
    </article>
<?php } ?>


<!-- Draw block with messages -->
<?php function drawMessagesBlockMessages(Chat $chat, $current_user_id, $current_item): void { ?>
    <section id="messages_block">
        <h2>Chat with: <?= $chat->chat_partner->username ?></h2>

        <?php if ($chat->getChatPartnerId($current_user_id) != $current_item->user_id) { ?>
            <a href="../pages/item.php?item_id=<?= $current_item->id ?>">Back to chats</a>
        <?php } ?>
        
        <?php drawMessages($chat, $current_user_id, $current_item->id); ?>

        <form action="" method="post" class="message_form" id="message_form" data-chat_id="<?= $chat->id ?>"
              data-last_message_id="<?= $chat->getLastMessageId() ?>">

            <img src="../assets/img/file-plus.svg" alt="Add file" id="attach_file">
            <img src="../assets/img/exchange.svg" alt="Exchange button" id="offer_exchange">


            <textarea name="text" id="message_field" cols="50" rows="1" placeholder="Type here."></textarea>
            <input type="hidden" name="chat_id" value="<?= $chat->id ?>" id="chat_id_field">
            <input type="hidden" name="item_id" value="<?= $current_item->id ?>">
            <input type="hidden" name="from_user_id" value="<?= $current_user_id ?>">
            <input id="to_user_id" type="hidden" name="to_user_id" value="<?= $chat->getChatPartnerId($current_user_id) ?>">

            <button type="submit">
                <img src="../assets/img/send-message.svg" alt="Send message" id="offer_exchange">
            </button>
        </form>

        <!-- TODO -->
        <div class="message_container" id="message_template">
            <div class="message my_message">
                <div>
                    <p class="text"></p>
                    <div class="message_time"></div>
                </div>
            </div>
        </div>

        <div class="message_container" id="message_template_partner">
            <div class="message partner_message">
                <div class="chat_partner_image">
                    <img src="<?= $chat->chat_partner->image_path ?>" alt="Patrner's foto">
                </div>
                <div>
                    <p class="text"></p>
                    <div class="message_time"></div>
                </div>
            </div>
        </div>

    </section>
    <script src="../js/messages.js"></script>
<?php } ?>


<?php function drawMessages(Chat $chat, int $current_user_id, int $current_item_id): void { ?>
    <section id="messages">
        <?php foreach ($chat->messages as $message) { ?>
            <article class="message <?= $message->isFromUserId($current_user_id) ? "my_message" : "partner_message" ?>">

                <?php if (!$message->isFromUserId($current_user_id)) { ?>
                    <figure class="profile_image">
                        <img src="<?= $chat->chat_partner->image_path ?>" alt="Partner's profile image">
                    </figure>
                <?php } ?>

                <div>
                    <p class="message_text"><?= htmlentities($message->text) ?></p>
                    <p class="message_time"><?= getTimePassed($message->date_time) ?></p>
                </div>

            </article>
        <?php } ?>
    </section>
<?php } ?>
