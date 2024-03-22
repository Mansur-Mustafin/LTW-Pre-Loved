<?php 
declare(strict_types=1);
?>

<?php function drawFilter() { ?>
    <div id="related">
        <h2>Filter</h2>
        <form action="#" method="get" id="filter">

            <div class="filter-element">
                <label>
                    <input type="checkbox">
                    <h4>Price</h4>
                </label>
                <ul>
                    <li><label><input type="checkbox" name="brand" value="apple">Apple</label></li>
                    <li><label><input type="checkbox" name="brand" value="asus">Asus</label></li>
                    <li><label><input type="checkbox" name="brand" value="razer">Razer</label></li>
                </ul>
            </div>

            <div class="filter-element">
                <label>
                    <input type="checkbox">
                    <h4>Brands</h4>
                </label>
                <ul>
                    <li><label><input type="checkbox" name="brand" value="apple">Apple</label></li>
                    <li><label><input type="checkbox" name="brand" value="asus">Asus</label></li>
                    <li><label><input type="checkbox" name="brand" value="razer">Razer</label></li>
                </ul>
            </div>

            <div class="filter-element">
                <label>
                    <input type="checkbox">
                    <h4>Models</h4>
                </label>
                <ul>
                    <li><label><input type="checkbox" name="brand" value="Macbook">Macbook</label></li>
                    <li><label><input type="checkbox" name="brand" value="ROG">ROG</label></li>
                    <li><label><input type="checkbox" name="brand" value="TUF">TUF</label></li>
                    <li><label><input type="checkbox" name="brand" value="ZenBook">ZenBook</label></li>
                </ul>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
<?php } ?>