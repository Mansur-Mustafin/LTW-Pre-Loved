<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/Session.php');

$session = new Session();

require_once (__DIR__ . '/../templates/common.tpl.php');
?>

<?php function drawDashboard(array $items,array $transactions,float $earnings): void
{ ?>
    <section id="dashboard">
        <div class="previous-page">
            <a href="../pages/profile.php">
                Go back
            </a>
        </div>
        <div>
            <ul>
                <li>
                    <p class="dashboard-tag">Earnings</p>
                    <p class="dashboard-value">$<?=$earnings?></p>
                </li>
                <li>
                    <p class="dashboard-tag">Sold Items</p>
                    <p class="dashboard-value"><?=sizeof($transactions)?></p>
                </li>
                <li>
                    <p class="dashboard-tag">Items</p>
                    <p class="dashboard-value"><?=sizeof($items)?></p>
                </li>
            </ul>
        </div>
    </section>
    <div id="dashboard-graph"></div>
<?php }?>