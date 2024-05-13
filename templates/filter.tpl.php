<?php 
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawFilter(Session $session, array $categories, array $brands, array $sizes, array $conditions) { ?>
    <p id="session_id"><?= $session->getId()?></p>
    <aside id="related">
        <h2>Filter</h2>
        <form action="#" method="get" id="filter">

            <?php 
                draw_filter_element('Categories', $categories, 'category'); 
                draw_filter_element('Brands', $brands, 'brand'); 
                draw_filter_element('Size', $sizes, 'size'); 
                draw_filter_element('Condition', $conditions, 'condition'); 
            ?>

            <button type="submit">Submit</button>
        </form>
    </aside>
<?php } ?>


<?php function draw_filter_element(string $title, array $elements, string $name_elemrnt) { ?>

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