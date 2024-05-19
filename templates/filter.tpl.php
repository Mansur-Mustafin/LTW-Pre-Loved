<?php 
declare(strict_types=1);
require_once(__DIR__ . '/../utils/Session.php');
?>

<?php function drawFilter(Session $session, array $categories, array $brands, array $sizes, array $conditions): void
{ ?>
    <p id="session_id"><?= $session->getId()?></p>
    <aside id="related">
        <button class="close-sidebar">
            <img src="../assets/img/close.svg">
        </button>

        <button class="open-filter"></button>
        <div class="aside-box filter-body">
            <h2>Filter</h2>
            <form action="#" method="get" id="filter">

                <?php 
                    draw_filter_element('Categories', $categories, 'category'); 
                    draw_filter_element('Brands', $brands, 'brand'); 
                    draw_filter_element('Size', $sizes, 'size'); 
                    draw_filter_element('Condition', $conditions, 'condition'); 
                ?>
                <div class='buttons'>
                    <button type="submit">Submit</button>
                </div>
                
            </form>
            <div class="slider-range">
                <input type="range" min="0" max="3000" value="3000" id='slider'>
                <p >Max price: <span id="current-max-price">3000</span> $</p>
            </div>
    </aside>
<?php } ?>


<?php function draw_filter_element(string $title, array $elements, string $name_elemrnt): void
{ ?>

    <article class="filter-element">
        <label class="hover-element">
            <input type="checkbox">
            <h4><?=$title?></h4>
        </label>
        <ul>

        <?php foreach($elements as $element) { ?>
            <li>
                <label>
                    <input type="checkbox" name="<?= $name_elemrnt ?>/<?= htmlspecialchars($element['name']) ?>" value="">
                    <?= htmlspecialchars($element['name']) ?>
                </label>
            </li>
        <?php } ?>

        </ul>
    </article>

<?php } ?>