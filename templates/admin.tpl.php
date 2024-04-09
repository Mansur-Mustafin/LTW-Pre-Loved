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
                <a href="../pages/admin.php?value=user">
                    <p>User</p>
                </a>                
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=statistics">
                    <p>Statistics</p>
                </a>    
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=transactions">
                    <p>Transactions</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=comments">
                    <p>Comments</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=reviews">
                    <p>Reviews</p>
                </a>
            </li>
            <li class="admin-item">
                <a href="../pages/admin.php?value=misc">
                    <p>Misc</p>
                </a>
            </li>
        </ul>
    </aside>
<?php } ?>

<?php function drawUsersAdmin() {?>
    <?php foreach($allUsers as $user) {?>
        
    <?php }?>
<?php }?>

<?php function drawStatisticsAdmin() {?>
    <?php echo "drawStatistics"?>
<?php }?>

<?php function drawTransactionsAdmin() {?>
    <?php echo "drawTransactions"?>
<?php }?>

<?php function drawCommentsAdmin() {?>
    <?php echo "drawComments"?>
<?php }?>

<?php function drawReviewsAdmin() {?>
    <?php echo "drawReviews"?>
<?php }?>

<?php function drawMiscAdmin() {?>
    <?php echo "drawMisc"?>
<?php }?>
