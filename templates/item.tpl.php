<?php 
declare(strict_types=1);

require_once(__DIR__ . '/../core/item.class.php');
require_once(__DIR__ . '/../utils/utils.php');
?>

<?php function drawItems(array $items, 
                        Session $session, 
                        string $title,
                        array $items_in_cart = array(), 
                        array $items_in_wish_list = array(),
                        int $page_index = 0, 
                        bool $has_more_pages = false): void 
{ ?>
    <section id="items">
        <?php if (empty($items)){ ?>
            <h2>TODO handle each page item.tpl.php line 11 <?=$title?></h2>
        <?php } else { ?>
            <h2 id="title"><?=$title?></h2>
            <div id="search-wrapper">
                <img src="../assets/img/search.svg" alt="search-bar" id="search-icon">
                <input class="search" id="item-search" type="text">
            </div>
            <div id="item-list">
                <?php foreach($items as $item){
                    $in_cart = in_array($item->id, $items_in_cart);
                    $in_wishlist = in_array($item->id, $items_in_wish_list);


                drawItem($item, $session, $title, $in_cart, $in_wishlist);
            } ?>
            <!-- Pagination Controls -->
            <?php if (sizeof($items) >= 10 || $page_index != 0) { ?>
                <nav id='pagination-control'>
                    <button <?= $page_index == 0 ? "disabled" : "" ?> onclick="window.location.href='?page=<?=$page_index - 1?>'" >
                        <img src="../assets/img/previous.svg" alt="Previous">
                    </button>

                    <span>Page <?=$page_index?></span>

                    <button <?= !$has_more_pages ? "disabled" : "" ?> onclick="window.location.href='?page=<?=$page_index + 1?>'" >
                        <img src="../assets/img/previous.svg" alt="Next" style='transform: rotate(180deg);'>
                    </button>
                </nav>
            <?php } ?>
        <?php } ?>
    </section>
<?php } ?>


<?php function drawItem(Item $item, Session $session, string $title, bool $in_cart, bool $in_wish_list): void 
{
    $main_image = $item->getImagesArray()[0]; ?>
    
    <article class="item fly" data-id="<?= $item->id ?>">
        <img src=<?=htmlspecialchars($main_image)?> alt="Item Image">
        <a href="../pages/item.php?item_id=<?=$item->id?>">
            <h3><?=htmlspecialchars($item->title)?></h3>
        </a>
        <div>
            <ul class='tags'>
                <?php foreach ($item->tags as $tag) { ?>
                    <li><?=htmlspecialchars($tag)?></li>
                <?php } ?>
                
                <?php if($item->condition){?> <li>  <?=htmlspecialchars($item->condition);?> </li> <?php } ?>
                <?php if($item->size){?> <li> <?=htmlspecialchars($item->size);?> </li> <?php } ?>
                <?php if ($item->tradable) { ?>
                    <li>Tradable</li>
                <?php } else { ?>
                    <li>Not tradable</li>
                <?php } ?>
            </ul>
            <p><?= htmlspecialchars(substr($item->description, 0, 100)) . 
                   (strlen($item->description) > 100 ? '...' : '') ?></p>
            <p><?=getTimePassed($item->created_at)?></p>
        </div>
        <div class="top-right-element"><p><?=htmlspecialchars(number_format($item->price, 2))?></p><p>$</p></div>
        <?php draw_buttons_item($item, $session, $title, $in_cart, $in_wish_list); ?>
    </article>
<?php } ?>


<?php
// TODO: change problem with title SOLID
function draw_buttons_item(Item $item, Session $session, string $title, bool $in_cart, bool $in_wish_list) 
{   
    $uri = '../actions/action_item_status.php';

    $cartHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'cart-toggle',
    ]);

    $wishlistHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'wishlist-toggle',
    ]);

    $deleteHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'delete',
    ]);
    
    if ($session->isLoggedIn()) { ?>
        <form>
            <?php if ($title == 'Find what you want to buy!' || $title == 'Your Wishlist!' || $title == 'Time to buy!'): ?>
                <a href="<?= $cartHref ?>" class="item-action button <?= $in_cart ? "selected" : "" ?>">
                    <img src="../assets/img/shopping-cart.svg" alt="Add to Cart">
                </a>
                <a href="<?= $wishlistHref ?>" class="item-action button <?= $in_wish_list ? "selected" : "" ?>">
                    <img src="../assets/img/love.svg" alt="Add to Wishlist">
                </a>
            <?php elseif ($title == 'Your items to sell'): ?>
                <a href="<?= $deleteHref ?>" class="item-action button">
                    <img src="../assets/img/trash.svg" alt="Delete Item">
                </a>
            <?php endif; ?>
        </form>
    <?php }
} ?>


<?php function drawItemMain(Item $item, Session $session, bool $in_cart, bool $in_wish_list) 
{
    $main_image = $item->getImagesArray()[0];
    $uri = '../actions/action_item_status.php';

    $cartHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'cart-toggle',
    ]);

    $wishlistHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'wishlist-toggle',
    ]);

    $deleteHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'delete-main',
    ]);

    ?>
    <aside>
    <article class="item-main">

        <h3><?=htmlspecialchars($item->title)?></h3>
        <div class="top-right-element"><p><?=htmlspecialchars(number_format($item->price, 2))?></p><p>$</p></div>

        <img src=<?=htmlspecialchars($main_image)?> alt="Item Image">
        
        <label>brand:
            <p><?= htmlspecialchars($item->brand) ?></p>
        </label>
        <label>model:
            <p><?= htmlspecialchars($item->model) ?></p>
        </label>
        <label>condition:
            <p><?= htmlspecialchars($item->condition)?></p>
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

        <p><?=getTimePassed($item->created_at)?></p>
        
    </article>
    
    
    <?php if ($session->isLoggedIn()) { ?>
        <form id="editing-item">
        <div class='buttons'>
            <?php if ($session->getId() == $item->user_id): ?>
                <a href="<?= $deleteHref ?>" class="item-action button">
                    Delete Item
                </a>
                <a href="" class="item-action button">
                    Edit Item
                </a>
            <?php else: ?>
                <a href="<?= $cartHref ?>" class="item-action button <?= $in_cart ? "selected" : "" ?>">
                    <?=$in_cart ? 'Remove ShoppingCart' : 'Add ShoppingCart'?>
                </a>
                <a href="<?= $wishlistHref ?>" class="item-action button <?= $in_wish_list ? "selected" : "" ?>">
                    <?=$in_wish_list ? 'Remove WishList' : 'Add WishList'?>
                </a>
            <?php endif; ?>
        </div>
        </form>
    <?php } ?>

    </aside>
<?php } ?>
