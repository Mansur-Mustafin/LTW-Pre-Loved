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
        <img src=<?=htmlspecialchars($main_image)?> alt="Account Profile Picture">
        <a href="../pages/item.php?item_id=<?=$item->id?>">
            <h3><?=htmlspecialchars($item->title)?></h3>
        </a>
        <div>
            <ul class='tags'>
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
        <div class="price"><p><?=htmlspecialchars(number_format($item->price, 2))?></p><p>$</p></div>
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


<?php function drawItemMain(Item $item, Session $session, bool $in_cart, bool $in_wish_list) {
    $main_image = $item->getImagesArray()[0];

    ?>
    <aside>
    <article class="item-main">

        <h3><?=htmlspecialchars($item->title)?></h3>
        <div class="price"><p><?=htmlspecialchars(number_format($item->price, 2))?></p><p>$</p></div>

        <img src=<?=htmlspecialchars($main_image)?> alt="Item Image">
        
        <label>brand:
            <p><?= htmlspecialchars($item->brand) ?></p>
        </label>
        <label>model:
            <p><?= htmlspecialchars($item->model) ?></p>
        </label>
        <label>condition:
            <p><?= htmlspecialchars($item->condition) ?></p>
        </label>
        <label>size:
            <p><?= htmlspecialchars($item->size) ?></p>
        </label>
        
        <ul class='tags'>
            <?php foreach ($item->tags as $tag) { ?>
                <li><?=htmlspecialchars($tag)?></li>
            <?php } ?>
            <?php if ($item->tradable) { ?>
                <li>Tradable</li>
            <?php } else { ?>
                <li>Not tradable</li>
            <?php } ?>
        </ul>
        
        <label>description:
            <p><?= htmlspecialchars($item->description) ?></p>
        </label>

        <p><?=$item->getTimePassed()?></p>
        
        
    </article>

    <!-- TODO -->
    <form action="../actions/action_item_status.php" id="editing-item" method="post">
        <?php if ($session->isLoggedIn()){ ?>
            <input type="hidden" name='user-id' value='<?= $session->getId() ?>'>
            <input type="hidden" name='item-id' value='<?= $item->id ?>'>
        <?php } ?>
       
        <div class='buttons'>
            <?php if ($session->isLoggedIn() && $session->getId() == $item->user_id){ ?>
                <button type='button' name="edit" value="item">Edit Item</button>
                <button type='submit' name="action" value="delete-main">Delete Item</button>
            <?php } else if($session->isLoggedIn()) { ?>

                <button type='submit' name='action' value='cart-<?=$in_cart ? 'delete' : 'add'?>'>
                    <?=$in_cart ? 'Remove ShoppingCart' : 'Add ShoppingCart'?>
                </button>
                <button type='submit' name='action' value='wishlist-<?=$in_wish_list ? 'delete' : 'add'?>'>
                    <?=$in_wish_list ? 'Remove WishList' : 'Add WishList'?>
                </button>

            <?php } ?>
        </div>
    </form>
    </aside>
<?php } ?>
