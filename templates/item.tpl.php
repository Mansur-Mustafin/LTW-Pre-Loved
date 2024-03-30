<?php 
declare(strict_types=1);

require_once(__DIR__ . '/../core/item.class.php')
?>

<?php function drawItems(array $items, Session $session, string $title, 
                         array $items_in_cart = array(), array $items_in_wish_list = array()) {?>
    <section id="items">
        <?php if (empty($items)){ ?>
            <h2>TODO handle each page item.tpl.php line 11 <?=$title?></h2>
        <?php } else { ?>
            <h2><?=$title?></h2>
            <?php foreach($items as $item){
                $in_cart = in_array($item->id, $items_in_cart);
                $in_wishlist = in_array($item->id, $items_in_wish_list);

                drawItem($item, $session, $title, $in_cart, $in_wishlist);
            } ?>
        <?php } ?>
    </section>
<?php } ?>


<?php function drawItem(Item $item, Session $session, string $title, bool $in_cart, bool $in_wish_list) { 
    $main_image = $item->getImagesArray()[0];
    ?>
    <article class="item">
        <img src=<?=htmlspecialchars($main_image)?> alt="Item Image">
        <a href="../pages/item.php?item_id=<?=$item->id?>">
            <h3><?=htmlspecialchars($item->title)?></h3>
        </a>
        <div>
            <ul>
                <?php foreach ($item->tags as $tag) { ?>
                    <li><?=htmlspecialchars($tag)?></li>
                <?php } ?>
                <li><?= htmlspecialchars($item->condition) ?></li>
                <li><?= htmlspecialchars($item->size) ?></li>
                <?php if ($item->tradable) { ?>
                    <li>Tradable</li>
                <?php } else { ?>
                    <li>Not tradable</li>
                <?php } ?>
            </ul>
            <p><?= htmlspecialchars(substr($item->description, 0, 100)) . 
                   (strlen($item->description) > 100 ? '...' : '') ?></p>
            <p><?=$item->getTimePassed()?></p>
        </div>
        <p class="price"><?=htmlspecialchars(number_format($item->price, 2))?></p><p class="coin">$</p>
        <?php draw_buttons_item($item, $session, $title, $in_cart, $in_wish_list); ?>
    </article>
<?php } ?>


<?php 
function draw_buttons_item(Item $item, Session $session, string $title, bool $in_cart, bool $in_wish_list) {
    if ($session->isLoggedIn()) { ?>
        <form action="../actions/action_item_status.php" method='post'>
            <input type="hidden" name='user-id' value='<?= $session->getId() ?>'>
            <input type="hidden" name='item-id' value='<?= $item->id ?>'>
            
            <?php if ($title == 'Find what you want to buy!' || $title == 'Your Wishlist!' || $title == 'Time to buy!') { ?>
                <button type='submit' name='action' value='cart-<?=$in_cart ? 'delete' : 'add'?>' class='<?= $in_cart ? "selected" : "" ?>'>
                    <img src="../assets/img/shopping-cart.svg" alt="Add to Cart">
                </button>
                <button type='submit' name='action' value='wishlist-<?=$in_wish_list ? 'delete' : 'add'?>' class='<?= $in_wish_list ? "selected" : "" ?>'>
                    <img src="../assets/img/love.svg" alt="Add to Wishlist">
                </button>
            <?php } elseif ($title == 'Your items to sell') { ?>
                <button type='submit' name='action' value='delete'><img src="../assets/img/trash.svg" alt="Delete Item"></button>
            <?php }
            // TODO Add more pages later
            ?>
        </form>
    <?php }
} ?>

