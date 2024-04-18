<?php

declare(strict_types=1);

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../pages/admin.php');
?>

<?php function drawSideBar() {?>
    <aside id="admin-nav-bar">
        <a href="" id="close-admin-nav-bar">
            <p></p>
        </a>
        <ul>
            <li class="admin-item">
                <a href="../pages/admin.php?value=users">
                    <p>Users</p>
                </a>                
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=statistics">
                    <p>Statistics</p>
                </a>    
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=tags">
                    <p>Tags</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=categories">
                    <p>Categories</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=brands">
                    <p>Brands</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=condition">
                    <p>Conditions</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=items">
                    <p>Items</p>
                </a>
            </li>
        </ul>
    </aside>
<?php } ?>

<?php function drawUsersAdmin(array $allUsers) {?>
    <section class="admin-info">
        <input id="admin-search-bar" type="text">
        <!-- TODO: FIND A WAY TO REMOVE THIS -->
        <div id="users-admin">
            <?php foreach($allUsers as $user) { ?>
                <article class="element item">
                    <img class="profile-img" src=<?= htmlspecialchars($user->image_path) ?> alt="User Image">
                    <div class="user-info">
                        <div class="user-tags">
                            <p class="username"><?= htmlspecialchars($user->username)?></p>
                            <?php if($user->admin_flag) {?>
                                <img src="../assets/img/star.svg" alt="Admin Tag">
                            <?php } ?>
                            <?php if($user->banned) {?>
                                <img src="../assets/img/banned.svg" alt="Banned Tag">
                            <?php }?>
                        </div>
                        <p class="email"><?= htmlspecialchars($user->email) ?></p>
                        <p class="phonenumber"> <?= htmlspecialchars($user->phonenumber) ?></p>
                        <p class ="address"> <?= htmlspecialchars($user->address) ?> </p>
                    </div>
                    <div class="buttons">
                        <?php if(!$user->admin_flag && !$user->banned) {?>
                            <form action="../actions/action_make_user_admin.php" method="post">
                                <button type="submit" name="username" value="<?= $user->username ?>">Make Admin</button>
                            </form>
                        <?php }?>
                        <?php if(!$user->banned && !$user->admin_flag) {?>
                            <form action="../actions/action_ban_user.php" method="post">
                                <button type="submit" name="username" value="<?= $user->username ?>">Ban</button>
                            </form>
                        <?php } else if($user->banned && !$user->admin_flag){?>
                            <form action="../actions/action_unban_user.php" method="post">
                                <button type="submit" name="username" value="<?= $user->username ?>">Unban</button>
                            </form>
                        <?php }?>
                    </div>
                </article>
            <?php } ?>
        </div>

    </section>
<?php }?>

<?php function drawStatisticsAdmin() {?>
    <section class="admin-info">
        <section id="analytics-admin"></section>
        <section id="analytics-charts"></section>
    </section>
<?php }?>

<?php function drawEntitiesAdmin(array $entities,string $type) {?>
    <section class="admin-info">
        <button id="add-tag">Add</button>
        <div id="entities-admin">
            <p id="type"><?= $type ?></p>
            <?php foreach($entities as $entity) { ?>
                <article class="element entity item" >
                    <p><?= $entity["name"] ?></p>
                    <form action="../actions/action_delete_entity.php" method="post">
                        <button type="submit" name="typeValue" value="<?= $type ?>/<?=$entity["id"]?>">Remove</button>
                    </form>
                </article>
            <?php } ?>
        </div>
    </section>
<?php }?>

<?php function drawItemsAdmin(array $items) {?>
    <section class="admin-info">
            <input id="admin-search-bar" type="text">
        <div id="items-admin">
            <!-- TODO: FIND A WAY TO REMOVE THIS -->
            <?php foreach($items as $item) { 
                $main_image = $item->getImagesArray()[0];
                $joined_tags = join(", ",$item->tags);?>
                <article class ="element product item">
                    <img src="<?=htmlspecialchars($main_image)?>" alt="product-picture">

                    <div class="product-info">
                        <a href="../pages/item.php?item_id=<?=$item->id?>" class="product-title"><?= $item->title ?> <span class="product-id"><?= $item->id ?></span></a>

                        <p>Brand: <?= $item->brand ?></p>
                        <p>Price: <span class="product-price"><?= $item->price ?></span></p>
                        <p>Tradable: <?= $item->tradable ? "Tradable" : "not Trabable" ?></p>
                        <p>Created-at: <?= $item->created_at?></p>
                        <p>Condition: <?= $item->condition?></p>
                        <p>Model: <?= $item->model ?></p>
                        <p>Category: <?= $item->category ?></p>
                        <p>Size: <?= $item->size ?></p>
                        <p>Tags: <?= $joined_tags?></p>
                        <p>Description: <?= $item->description ?></p>
                    </div>

                    <div class="buttons">
                        <form action="../actions/action_delete_product.php" method="post">
                            <button type="submit" name="product_id" value=<?= $item->id ?>>Remove</button>
                        </form>
                    </div>
                </article> 
            <?php } ?>
        </div>
    </section>
<?php }?>
