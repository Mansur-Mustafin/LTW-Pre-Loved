<?php 
declare(strict_types=1);

require_once(__DIR__ . '/../core/item.class.php');
require_once(__DIR__ . '/../utils/utils.php');
?>

<?php function drawItems(
    array $items,
    Session $session,
    string $title,
    bool $isCurrentUserPage,
    array $items_in_cart = [],
    array $items_in_wish_list = [],
    int $page_index = 0,
    bool $has_more_pages = false,
    string $place = null,
): void { ?>
    <section id="items">
        <?php if (empty($items)) { ?>
            <h2>TODO handle each page item.tpl.php line 11 <?=$title?></h2>
        <?php } else { ?>
            <h2 id="title"><?= $title ?></h2>
            <div id="search-wrapper">
                <img src="../assets/img/search.svg" alt="search-bar" id="search-icon">
                <input class="search" id="item-search" type="text">
            </div>
            <div id="item-list">
                <?php foreach($items as $item){
                    $in_cart = in_array($item->id, $items_in_cart);
                    $in_wishlist = in_array($item->id, $items_in_wish_list);

                    drawItem($item, $session, $title, $in_cart, $in_wishlist, $isCurrentUserPage, $place);
                } ?>
            </div>
            <!-- Pagination Controls -->
            <?php pagination(count($items), $page_index, $has_more_pages) ?>
        <?php } ?>
    </section>
<?php } ?>

<?php function drawItemsGroups(
    array $items,
    Session $session,
    string $title,
    bool $isCurrentUserPage,
    array $items_in_cart = array(),
    array $items_in_wish_list = array(),
    int $page_index = 0,
    bool $has_more_pages = false,
): void { ?>
    <section id="items">
        <?php if (empty($items)){ ?>
            <h2>TODO handle each page item.tpl.php line 11 <?=$title?></h2>
        <?php } else { ?>
            <h2 id="title"><?= $title ?></h2>
            <div id="search-wrapper">
                <img src="../assets/img/search.svg" alt="search-bar" id="search-icon">
                <input class="search" id="item-search" type="text">
            </div>
            <div id="item-list">
                <?php

                $itemsCount = 0;

                foreach($items as $vendorName => $itemsGroup) {

                    $itemsCount += count($itemsGroup);
                    $uri = '../pages/profile.php';

                    $verdorHref = urlTo($uri, [
                        'id' => $itemsGroup[0]->user_id,
                    ]);
                    
                    ?>
                    <div>
                        Sold by: 
                        <a href="<?=$verdorHref?>">
                            <span class='link'><?= $vendorName ?><span>
                        </a>
                    </div>
                    <?php

                    foreach ($itemsGroup as $item) {
                        $in_cart = in_array($item->id, $items_in_cart);
                        $in_wishlist = in_array($item->id, $items_in_wish_list);

                        drawItem($item, $session, $title, $in_cart, $in_wishlist, $isCurrentUserPage);
                    }
                }

                ?>
            </div>
            <!-- Pagination Controls -->
            <?php pagination($itemsCount, $page_index, $has_more_pages) ?>
        <?php } ?>
    </section>
<?php } ?>

<?php function pagination($itemsCount, $page_index, $has_more_pages) { ?>
    <?php if ($itemsCount >= 10 || $page_index != 0) { ?>
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


<?php function drawItem(
    Item $item,
    Session $session,
    string $title,
    bool $in_cart,
    bool $in_wish_list,
    bool $isCurrentUserPage,
    string $place = null,
): void {
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
        <?php draw_buttons_item($item, $session, $title, $in_cart, $in_wish_list, $isCurrentUserPage, $place); ?>
    </article>
<?php } ?>

<?php
// TODO: change problem with title SOLID
function draw_buttons_item(Item $item, Session $session, string $title, bool $in_cart, bool $in_wish_list, bool $isCurrentUserPage, $place = null)
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
            <?php if ($title == 'Find what you want to buy!' || $title == 'Your Wishlist!' || $title == 'Time to buy!' || ($title == 'Your items to sell' && !$isCurrentUserPage)): ?>
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
            <?php elseif ($place == 'transactions'): ?>
                <a target="_blank" href="<?= urlTo('../actions/action_bill.php', ['item-id' => $item->id]) ?>" class="button">
                    <img src="../assets/img/file-plus.svg" alt="Get Bill">
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
    $editHref = urlTo($uri, [
        'item-id' => $item->id,
        'action' => 'edit-main',
    ]);

    ?>
    <aside>
    <article class="item-main">

        <h3><?=htmlspecialchars($item->title)?></h3>
        <div class="top-right-element"><p><?=htmlspecialchars(number_format($item->price, 2))?></p><p>$</p></div>

        <div id="image-nav">
        <?php foreach($item->getImagesArray() as $image) { ?>
            <figure>
            <img src="<?=htmlspecialchars($image, ENT_QUOTES, 'UTF-8')?>" alt="Item Image" style="max-width: 256px; max-height: 256px;">
            </figure>
        <?php } ?>
        </div>
        
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
                <a href="<?= $editHref ?>" class="item-action button">
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
<?php function drawAddItem(PDO $db, Session $session) { ?>
  <form id="add-item-form" action="../actions/action_add_item.php" method="post" enctype="multipart/form-data">
    <h2>Add new Item</h2>
    <div class="error-messages">
        <?= drawErrors($session->getErrorMessages()) ?>
    </div>
    <label for="item-name">Title:</label>
    <input type="text" id="item-title" name="item-title" required>
    <label for="item-description">Description:</label>
    <textarea id="item-description" name="item-description"></textarea>
    <label for="image-paths">Images:</label>
    <input type="file" name="item-images[]" id="item-images" accept="image/*" multiple>
    <label for="item-category">Category:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Categories');

    $categories = $stmt->fetchAll();
    ?>
    <select id="item-category" name="item-category" required>
    <?php foreach($categories as $category){ ?>
    <option value="<?= htmlspecialchars($category['name']); ?>">
        &nbsp<?= htmlspecialchars($category['name']); ?></option>
        <?php } ?>
    </select>
    <label for="item-brand">Brand:</label>
    <input type="text" id="item-brand" name="item-brand" required>
    <label for="item-model">Model:</label>
    <input type="text" id="item-model" name="item-model" required>
    <label for="item-condition">Condition:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Condition');

    $conditions = $stmt->fetchAll();
    ?>
    <select id="item-condition" name="item-condition">
    <?php foreach($conditions as $condition){ ?>
    <option value="<?= htmlspecialchars($condition['name']); ?>">
        &nbsp<?= htmlspecialchars($condition['name']); ?></option>
        <?php }?>
    </select>
    <label for="item-price" min="0" inputmode="numeric">Price:</label>
    <input type="number" id="item-price" name="item-price" step="0.01" required>
    <label for="tradableItem">Tradable item:</label>
    <input type="checkbox" id="tradable-item" name="tradable-item"><br>
    <label for="item-size">Size:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Size');

    $sizes = $stmt->fetchAll();
    ?>
    <select id="item-size" name="item-size">
    <?php foreach($sizes as $size){ ?>
    <option value="<?= htmlspecialchars($size['name']); ?>">
        &nbsp<?= htmlspecialchars($size['name']); ?></option>
        <?php }?>
    </select>
    <div id="item-tag-wrapper">
        <label for="item-tags">Item Tags:</label>
        <?php
            $stmt = $db->query('SELECT id, name FROM Tags');
            $tags = $stmt->fetchAll();
        ?>
        <select id="item-tags" name="item-tags[]" multiple>
        <?php foreach ($tags as $tag){?>
            <option value="<?= htmlspecialchars($tag['name']); ?>">
            &nbsp;<?= htmlspecialchars($tag['name']); ?></option>
        <?php }?>
        </select>
    </div>
    <div id="submit-item-button"><button type="submit">Add Item</button></div>
  </form>
<?php } ?>

<?php function drawEditItem(PDO $db, int $itemId, Session $session) { ?>
  <form id="add-item-form" action="../actions/action_edit_item.php?item_id=<?php echo $itemId; ?>" method="post" enctype="multipart/form-data">
    <h2>Edit Item</h2>
    <div class="error-messages">
        <?= drawErrors($session->getErrorMessages()) ?>
    </div>
    <label for="item-name">Title:</label>
    <input type="text" id="item-title" name="item-title" required>
    <label for="item-description">Description:</label>
    <textarea id="item-description" name="item-description"></textarea>
    <label for="item-images">Images:</label>
    <input type="file" name="item-images[]" id="item-images" accept="image/*" multiple>
    <label for="item-category">Category:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Categories');

    $categories = $stmt->fetchAll();
    ?>
    <select id="item-category" name="item-category" required>
    <?php foreach($categories as $category){ ?>
    <option value="<?= htmlspecialchars($category['name']); ?>">
        &nbsp<?= htmlspecialchars($category['name']); ?></option>
        <?php } ?>
    </select>
    <label for="item-model">Model:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Models');

    $models = $stmt->fetchAll();
    ?>
    <select id="item-model" name="item-model" required>
    <?php foreach($models as $model){ ?>
    <option value="<?= htmlspecialchars($model['name']); ?>">
        &nbsp<?= htmlspecialchars($model['name']); ?></option>
        <?php } ?>
    </select>
    <label for="item-condition">Condition:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Condition');

    $conditions = $stmt->fetchAll();
    ?>
    <select id="item-condition" name="item-condition">
    <?php foreach($conditions as $condition){ ?>
    <option value="<?= htmlspecialchars($condition['name']); ?>">
        &nbsp<?= htmlspecialchars($condition['name']); ?></option>
        <?php }?>
    </select>
    <label for="item-price" min="0" inputmode="numeric">Price:</label>
    <input type="number" id="item-price" name="item-price" step="0.01" required>
    <label for="tradableItem">Tradable item:</label>
    <input type="checkbox" id="tradable-item" name="tradable-item"><br>
    <label for="item-size">Size:</label>
    <?php
    $stmt = $db->query('SELECT id, name FROM Size');

    $sizes = $stmt->fetchAll();
    ?>
    <select id="item-size" name="item-size">
    <?php foreach($sizes as $size){ ?>
    <option value="<?= htmlspecialchars($size['name']); ?>">
        &nbsp<?= htmlspecialchars($size['name']); ?></option>
        <?php }?>
    </select>
    <label for="item-tags">Item Tags:</label>
    <?php
        $stmt = $db->query('SELECT id, name FROM Tags');
        $tags = $stmt->fetchAll();
    ?>
    <select id="item-tags" name="item-tags[]" multiple>
    <div id="item-tag-wrapper">
        <label for="item-tags">Item Tags:</label>
        <?php
            $stmt = $db->query('SELECT id, name FROM Tags');
            $tags = $stmt->fetchAll();
        ?>
        <select id="item-tags" name="item-tags[]" multiple>
        <?php foreach ($tags as $tag){?>
            <option value="<?= htmlspecialchars($tag['name']); ?>">
            &nbsp;<?= htmlspecialchars($tag['name']); ?></option>
        <?php }?>
        </select>
    </div>
    <div id="submit-item-button"><button type="submit">Edit Item</button></div>
  </form>
<?php } ?>
