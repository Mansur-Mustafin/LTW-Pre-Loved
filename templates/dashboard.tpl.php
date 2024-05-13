<?php

declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');

$session = new Session();

require_once (__DIR__ . '/../templates/common.tpl.php');
?>

<?php function drawDashboard(array $items) 
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
                    <p class="dashboard-value">$15000</p>
                </li>
                <li>
                    <p class="dashboard-tag">Rating</p>
                    <p class="dashboard-value">5.00</p>
                </li>
                <li>
                    <p class="dashboard-tag">Sold Items</p>
                    <p class="dashboard-value">5</p>
                </li>
                <li>
                    <p class="dashboard-tag">Items</p>
                    <p class="dashboard-value">13</p>
                </li>
            </ul>
        </div>
    </section>
    <div id="dashboard-graph"></div>
<?php }?>