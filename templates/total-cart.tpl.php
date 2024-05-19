<?php

/** @var array $items */

declare(strict_types=1);

$vendors = [];
$totalPrice = 0;

foreach ($items as $item) {
    if (!isset($vendors[$item->user_id])) {
        $vendors[$item->user_id]['username'] = $item->username;
        $vendors[$item->user_id]['itemsTotalPrice'] = 0;
    }

    $totalPrice += $item->price;
    $vendors[$item->user_id]['itemsTotalPrice'] += $item->price;
}

?>

<aside id="related">
    <button class="close-sidebar">
            <img src="../assets/img/close.svg">
        </button>
    <div class="aside-box">
    <h2>Your Cart:</h2>
    <section class='card'>
        <p>
            Total items: <span class="text-right"><?= count($items) ?></span>
        </p>
        <p>
            Different vendors: <span class="text-right"><?= count($vendors) ?></span>
        </p>
        <ul>
            <?php foreach ($vendors as $vendor): ?>
                <li><?= $vendor['username'] ?>: <span class="text-right"><?= $vendor['itemsTotalPrice'] ?> $</span></li>
            <?php endforeach ?>
        </ul>
        <h3>Total: <span class="text-right"><?= $totalPrice ?> $</span></h3>
    </section>
    <?php if (count($items)): ?>
        <div class='buttons'>
            <?php if (!empty($isCart)) :?>
                <a href="../pages/shopping_checkout.php" class='button'>Proceed to pay</a>
            <?php else: ?>
                <button type="submit" form="buy" formaction="../actions/action_checkout.php" formmethod="post">
                    Pay Now!
                </button>
            <?php endif ?>
        </div>
    <?php endif ?>
    </div>
    
</aside>
