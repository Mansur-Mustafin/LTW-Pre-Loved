<?php 
declare(strict_types=1);
?>

<?php function drawItems(array $items, Session $session, string $title) {?>
    <section id="items">
        <?php if (empty($items)){ ?>
            <h2>You dont have items to sell, add one! TODO</h2>
        <?php } else { ?>
            <h2><?=$title?></h2>
            <?php foreach($items as $item){
                drawItem($item, $session);
            } ?>
        <?php } ?>
    </section>
<?php } ?>


<?php function drawItem($item, Session $session) { 
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
            <p><?= htmlspecialchars(substr($item->description, 0, 100)) . (strlen($item->description) > 100 ? '...' : '') ?></p>
            <p><?=$item->getTimePassed()?></p>
        </div>
        <p class="price"><?=htmlspecialchars(number_format($item->price, 2))?></p><p class="coin">$</p>
        <?php if ($session->isLoggedIn()) { ?>
            <form action="../actions/add_to_list" method='post'>
                <input type="hidden" name='user-id' value='<?= $session->getId() ?>'>
                <input type="hidden" name='item-id' value='<?= $item->id ?>'>
                <button type='submit' value='cart'><img src="../assets/img/shopping-cart.svg" alt="Add to Cart"></button>
                <button type='submit' value='wishlist'><img src="../assets/img/love.svg" alt="Add to Wishlist"></button>
            </form>
        <?php } ?>
    </article>
<?php } ?>
