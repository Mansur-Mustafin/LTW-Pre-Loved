<?php 
declare(strict_types=1);
?>

<?php function drawFilter(array $categories, array $brands, array $sizes, array $conditions) { ?>
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

    <div class="filter-element">
        <label>
            <input type="checkbox">
            <h4><?=$title?></h4>
        </label>
        <ul>

        <?php foreach($elements as $element) { ?>
            <li>
                <label>
                    <input type="checkbox" name="name_elemrnt" value="<?= htmlspecialchars($element['name']) ?>">
                    <?= htmlspecialchars($element['name']) ?>
                </label>
            </li>
        <?php } ?>

        </ul>
    </div>

<?php } ?>