<?php

/** @var array $items */
/** @var ShippingCosts $sc */
/** @var User $current_user */

declare(strict_types=1);

$vendors = [];
$totalPrice = 0;
$canProceedToPay = true;
$vendorsNoZip = [];

if (empty($current_user->zip_code)) {
    $canProceedToPay = false;
}

foreach ($items as $item) {
    if (!isset($vendors[$item->user_id])) {
        $vendors[$item->user_id]['username'] = $item->username;
        $vendors[$item->user_id]['itemsTotalPrice'] = 0;
        $vendors[$item->user_id]['itemsTotalShip'] = 0;
    }

    $user = getUser($db, $item->username);

    if (empty($user->zip_code) || !$canProceedToPay) {
        $canProceedToPay = false;
        $vendorsNoZip[$item->username] = 1;
    } else if ($vendors[$item->user_id]['itemsTotalShip'] == 0) {
        $cost = $sc->getDistance($user->zip_code, $current_user->zip_code);
        $totalPrice += $cost;
        $vendors[$item->user_id]['itemsTotalShip'] += $cost;
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
                <li>Shipping: <span class="text-right"><?= $vendor['itemsTotalShip'] ?> $</span></li>
            <?php endforeach ?>
        </ul>
        <h3>Total: <span class="text-right"><?= $totalPrice ?> $</span></h3>
    </section>
    <?php if (count($items)): ?>
        <div class='buttons'>
            <?php if (!empty($isCart)) : ?>
                <?php if ($canProceedToPay) { ?>
                    <a href="../pages/shopping_checkout.php" class='button'>Proceed to pay</a>
                <?php } else { ?>
                    <ul>
                        <?php if (empty($current_user->zip_code)) {?>
                            <li>Provide zip-code to calculate shipping costs.</li>
                        <?php }?>
                    <?php if (!empty($vendorsNoZip)) {
                        foreach ($vendorsNoZip as $name => $_){ ?>
                            <li><?= $name ?> didn't provide zip-code</li>
                        <?php }
                    } ?>
                    </ul>
                <?php } ?>
            <?php else: ?>
                <button type="submit" form="buy" formaction="../actions/action_checkout.php" formmethod="post">
                    Pay Now!
                </button>
            <?php endif ?>
        </div>
    <?php endif ?>
    </div>
    
</aside>
