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
        <img src=<?=$main_image?> alt="Item Image">
        <a href="../pages/item.php?item_id=<?=$item->id?>">
            <h3><?=$item->title?></h3>
        </a>
        <p><?=$item->description?></p>
        <p class="price"><?=$item->price?></p><p class="coin">$</p>
    </article>
<?php } ?>
