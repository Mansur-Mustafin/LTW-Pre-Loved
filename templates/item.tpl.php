<?php 
declare(strict_types=1);
?>

<?php function drawItems(array $items, Session $session) { ?>
    <section id="items">
        
    <?php foreach($items as $item){
        drawItem($item, $session);
    } ?>
    </section>
<?php } ?>


<?php function drawItem($item, Session $session) { ?>
    <article class="item">
        <img src="../assets/img/default_item.svg" alt="Item Image">
        <h3>Item Title</h3>
        <p>Description of the item...</p>
        <p class="price">300</p><p class="coin">$</p>
    </article>
<?php } ?>
