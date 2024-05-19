<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/Session.php');

$session = new Session();

require_once (__DIR__ . '/../templates/common.tpl.php');
require_once (__DIR__ . '/../pages/admin.php');
require_once (__DIR__ . '/../utils/Request.php');
?>

<?php function drawSideBar()
{ ?>
    <aside id="related">
        <button class="close-sidebar">
            <img src="../assets/img/close.svg">
        </button>

        <div class="aside-box">
            <h2>Pages</h2>

            <nav class="pages">

                <ul>

                    <li class="filter-element ">
                        <a href="../pages/admin.php?value=users">
                            <h4 class="hover-element">
                                Users
                            </h4>
                        </a>
                    </li>


                    <li class="filter-element">
                        <a href="../pages/admin.php?value=statistics">
                            <h4 class="hover-element">
                                Statistics
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element">
                        <a href="../pages/admin.php?value=tags">
                            <h4 class="hover-element">
                                Tags
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element">
                        <a href="../pages/admin.php?value=categories">
                            <h4 class="hover-element">
                                Categories
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element"><a href="../pages/admin.php?value=brands">
                            <h4 class="hover-element">
                                Brands
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element"><a href="../pages/admin.php?value=condition">
                            <h4 class="hover-element">
                                Conditions
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element"><a href="../pages/admin.php?value=size">
                            <h4 class="hover-element">
                                Sizes
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element"><a href="../pages/admin.php?value=items">
                            <h4 class="hover-element">
                                Items
                            </h4>
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
        
    </aside>
<?php } ?>


<?php function drawUsersAdmin(array $allUsers, Session $session): void
{ ?>
    <section class="admin-info">
        <div id="search-wrapper">
            <img src="../assets/img/search.svg" alt="search-bar" id="search-icon">
            <label for="user-admin-search"></label><input class="search" id="user-admin-search" type="text">
        </div>
        <!-- TODO: FIND A WAY TO REMOVE THIS -->
        <div id="users-admin">
            <?php foreach ($allUsers as $user) { ?>
                <article class="item">
                    <img class="profile-img" src="<?= htmlspecialchars($user->image_path) ?>" alt="User Image">
                    <h3><?= htmlspecialchars($user->username) ?></h3>
                    <div>
                        <ul class="tags">
                            <?php if ($user->admin_flag) { ?>
                                <li class='red-tag'>Admin</li>
                            <?php } ?>
                            <?php if ($user->banned) { ?>
                                <li class='red-tag'>Banned</li>
                            <?php } ?>
                        </ul>
                        <p>
                        <ul>
                            <li>
                                <label>Email:
                                    <?= htmlspecialchars($user->email) ?>
                                </label>
                            </li>
                            <li>
                                <label>Phone number:
                                    <?= htmlspecialchars($user->phonenumber) ?>
                                </label>
                            </li>
                            <li>
                                <label>Address:
                                    <?= htmlspecialchars($user->address) ?>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="top-right-element">
                    </div>


                    <form>
                        <?= Request::generateCsrfTokenInput() ?>
                        <?php if (!$user->banned && !$user->admin_flag) { ?>
                            <button type="submit" name="username" value="<?= $user->username ?>"
                                formaction="../actions/action_make_user_admin.php" formmethod="post">Promote</button>
                            <button type="submit" name="username" value="<?= $user->username ?>"
                                formaction="../actions/action_ban_user.php" formmethod="post">Ban</button>
                        <?php } else if ($user->banned && !$user->admin_flag) { ?>
                                <button type="submit" name="username" value="<?= $user->username ?>"
                                    formaction="../actions/action_unban_user.php" formmethod="post">Unban</button>
                        <?php } ?>
                    </form>
                </article>
            <?php } ?>
        </div>

    </section>
<?php } ?>

<?php function drawStatisticsAdmin()
{ ?>
    <section class="admin-info">
        <section id="analytics-admin"></section>
        <section id="analytics-charts"></section>
    </section>
<?php } ?>

<?php function drawEntitiesAdmin(array $entities, string $type): void
{ ?>
    <section class="admin-info">
        <button id="add-tag">Add</button>
        <div id="entities-admin">
            <p id="type"><?= $type ?></p>
            <ul class="tags">
                <?php foreach ($entities as $entity) { ?>
                    <li>
                        <p><?= $entity["name"] ?></p>
                        <form action="../actions/action_delete_entity.php" method="post" id="delete-entity-form">
                            <?= Request::generateCsrfTokenInput() ?>
                            <button type="submit" name="typeValue" value="<?= $type ?>/<?= $entity["id"] ?>">
                                <img src="../assets/img/trash.svg" alt="">
                            </button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </section>
<?php } ?>

<?php function drawItemsAdmin(array $items): void
{ ?>
    <section class="admin-info">
        <div id="search-wrapper">
            <img src="../assets/img/search.svg" alt="search-bar" id="search-icon">
            <label for="item-admin-search"></label><input class="search" id="item-admin-search" type="text">
        </div>
        <div id="items-admin">
            <!-- TODO: FIND A WAY TO REMOVE THIS -->
            <?php foreach ($items as $item) {
                $main_image = $item->getImagesArray()[0];
                $joined_tags = join(", ", $item->tags); ?>
                <article class="element product item">
                    <img src="<?= htmlspecialchars($main_image) ?>" alt="product-picture">
                    <a href="../pages/item.php?item_id=<?= $item->id ?>">
                        <h3>
                            <?= $item->title ?>
                            <span class="product-id"><?= $item->id ?></span>
                        </h3>
                    </a>

                    <div class="product-info">
                        <p>Brand: <?= $item->brand ?></p>
                        <p>Price: <span class="product-price"><?= $item->price ?></span></p>
                        <p>Tradable: <?= $item->tradable ? "Tradable" : "not Trabable" ?></p>
                        <p>Created-at: <?= date("Y-m-d H:i:s", $item->created_at) ?></p>
                        <p>Condition: <?= $item->condition ?></p>
                        <p>Model: <?= $item->model ?></p>
                        <p>Category: <?= $item->category ?></p>
                        <p>Size: <?= $item->size ?></p>
                        <p>Tags: <?= $joined_tags ?></p>
                        <p>Description: <?= $item->description ?></p>
                    </div>

                    <div class="top-right-element"></div>
                    <form action="../actions/action_delete_product.php" method="post">
                        <?= Request::generateCsrfTokenInput() ?>
                        <button type="submit" name="product_id" value=<?= $item->id ?>>Remove</button>
                    </form>

                </article>
            <?php } ?>
        </div>
    </section>
<?php } ?>