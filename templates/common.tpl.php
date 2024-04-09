<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawHeader(Session $session, string $title = ''){?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/layout.css" rel="stylesheet">
        <link href="../css/filter.css" rel="stylesheet">    
        <link href="../css/form.css" rel="stylesheet">
        <link href="../css/profile.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/item.css" rel="stylesheet">
        <link href="../css/admin.css" rel="stylesheet">
        <title>Matador OLX | <?=$title?></title>
        <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png">
    </head>
    <body>
        <header>

            <div id="logo">
                <a href="../">
                    <img src="../assets/img/logo.svg" alt="Matador OLX Logo" class="logo">
                    <h1>Matador OLX</h1>
                    <p>Sell and buy what you love!</p>
                </a>
            </div>
            
            <nav id="header-menu">
                <ul>
                <?php if ($session->isLoggedIn()) {?>
                    <li><img src="../assets/img/shopping-cart.svg" alt="cart-icon"><a href="../pages/shopping_cart.php">Shopping Cart</a></li>
                    <li><img src="../assets/img/love.svg" alt="cart-icon"><a href="../pages/wishlist.php">Wish List</a></li>
                    <li><img src="../assets/img/account-icon.svg" alt="account-icon"><a href="../pages/profile.php"><?=$session->getName();?></a></li>
                    <li><a href="../actions/action_logout.php"><img src="../assets/img/logout.svg" alt="account-icon"></a></li>
                <?php } else { ?>
                    <li><img src="../assets/img/account-icon.svg" alt="account-icon"><a href="../pages/login.php">Login</a></li>
                <?php } ?>
                </ul>
            </nav>

        </header>
        <main>

<?php } ?>


<?php function drawFooter() { ?>
    </main>
    <div id = "back-button"><a href="#">Back to the top</a></div>
    <footer>
        <nav class = "footer-list">
            <ul>
                <h3>Support</h3>
                <li><a href = "#">Who are we?</a></li>
                <li><a href = "#">Contact us</a></li>
                <li><a href = "#">Give us feedback!</a></li>
            </ul>
            <ul>
                <h3>Socials</h3>
                <li><a href = "#">Facebook</a></li>
                <li><a href = "#">Instagram</a></li>
                <li><a href = "#">X</a></li>
            </ul>
        </nav>
        <p>&copy; 2024 Matador OLX. All rights reserved.</p>
    </footer>
    
</body>
</html>

<?php } ?>


<?php function drawErrors(array $errors) { ?>
    <ul id='errors'>
        <?php foreach($errors as $error){ ?>
            <li><p><?=$error?></p></li>
        <?php } ?>
    </ul>
<?php } ?>

<?php function drawSuccessMsg(array $messages) { ?>
    <ul id='success'>
        <?php foreach($messages as $message){ ?>
            <li><p><?=$message?></p></li>
        <?php } ?>
    </ul>
<?php } ?>