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
                <a href="../pages/admin.php?value=items">
                    <p>Items</p>
                </a>
            </li>
        </ul>
    </aside>
<?php } ?>

<?php function drawUsersAdmin(array $allUsers) {?>
    <section id="user-admin-info">
        <?php foreach($allUsers as $user) { ?>
            <article class="item">
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
                    <?php if(!$user->admin_flag) {?>
                        <button>Make Admin</button>
                        <button>Ban</button>
                    <?php }?>
                </div>

            </article>
        <?php } ?>
    </section>
<?php }?>

<?php function drawStatisticsAdmin() {?>
    <?php echo "TODO"?>
<?php }?>

<?php function drawTagsAdmin() {?>
    <?php echo "TODO"?>
<?php }?>

<?php function drawItemsAdmin() {?>
    <?php echo "TODO"?>
<?php }?>
