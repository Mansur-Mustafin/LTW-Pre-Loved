<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/Session.php');
?>

<?php function drawHeader(Session $session, ?string $title = ''): void
{ ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/style.css" rel="stylesheet">
        
        <link href="../css/layout.css" rel="stylesheet">
        <link href="../css/search_bar.css" rel="stylesheet">
        <link href="../css/filter.css" rel="stylesheet">    
        <link href="../css/form.css" rel="stylesheet">
        <link href="../css/profile.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/item.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/admin.css" rel="stylesheet">
        <link href="../css/dashboard.css" rel="stylesheet">
        <link href="../css/messages.css" rel="stylesheet">
        <link href="../css/card.css" rel="stylesheet">

        <link href="../css/responsive.css" rel="stylesheet">    
        <link href="../css/responsive-tablet.css" rel="stylesheet">
        <link href="../css/responsive-mobile.css" rel="stylesheet">
        <title>Matador OLX | <?=$title?></title>
        <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png">
        <script type="text/javascript" src="../js/search_item.js" defer></script>
        <script type="text/javascript" src="../js/search_user.js" defer></script>
        <script type="text/javascript" src="../js/add_entity.js" defer></script>
        <script type="text/javascript" src="../js/filter.js" defer></script>
        <script type="text/javascript" src="../js/header.js" defer></script>
        <script type="text/javascript" src="../js/analytics_page.js" defer></script>
        <script type="text/javascript" src="../js/filter_item.js" defer></script>
        <script type="text/javascript" src="../js/add_item.js" defer></script>
        <script type="text/javascript" src="../js/dashboard.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    </head>
    <body data-csrf-token="<?=$session->getCsrfToken(); ?>">
        <header>
            <div class="header-top">
                <div class="header-button-wrap">
                    <button class="open-sidebar">
                        <img src="../assets/img/open-sidebar.svg">
                    </button>
                </div>

                <div id="logo">
                    <a href="../">
                        <img src="../assets/img/logo.svg" alt="Matador OLX Logo" class="logo">
                        <h1>Matador OLX</h1>
                        <p>Sell and buy what you love!</p>
                    </a>
                </div>

                <div class="header-button-wrap">
                    <button class="open-menu">
                        <i class="img"></i>
                    </button>
                </div>
            </div>

            <hr>
            
            <nav id="header-menu">
                <ul>
                <?php if ($session->isLoggedIn()) {?>
                    <?php if($session->isAdmin()) {?>
                        <li><a href="../pages/admin.php"><img src="../assets/img/control-centre.svg" alt="admin-icon">Admin Page</a></li>
                    <?php } ?>
                    <li><a href="../pages/add_item.php"><img src="../assets/img/plus-sign.svg" alt="plus-sign">Add item</a></li>
                    <li><a href="../pages/shopping_cart.php"><img src="../assets/img/shopping-cart.svg" alt="cart-icon">Shopping Cart</a></li>
                    <li><a href="../pages/wishlist.php"><img src="../assets/img/love.svg" alt="cart-icon">Wish List</a></li>
                    <li><a href="../pages/profile.php"><img src="../assets/img/account-icon.svg" alt="account-icon"><?=$session->getName();?></a></li>
                    <li><a href="../actions/action_logout.php"><img src="../assets/img/logout.svg" alt="account-icon"></a></li>
                <?php } else { ?>
                    <li><a href="../pages/login.php"><img src="../assets/img/account-icon.svg" alt="account-icon">Login</a></li>
                <?php } ?>
                </ul>
            </nav>

        </header>
        <main>

<?php } ?>


<?php function drawFooter() 
{ ?>
    </main>
    <div id = "back-button"><a href="#">Back to the top</a></div>
    <script src="../js/scroll_up.js"></script>
    <script src="../js/items_list.js"></script>
    <footer>
        <nav class = "footer-list">
            <ul>
                <li class="h3">Support</li>
                <li><a href = "#">Who are we?</a></li>
                <li><a href = "#">Contact us</a></li>
                <li><a href = "#">Give us feedback!</a></li>
            </ul>
            <ul>
                <li class="h3">Socials</li>
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


<?php function drawErrors(array $errors): void
{ ?>
    <ul id='errors'>
        <?php foreach($errors as $error){ ?>
            <li><p><?=$error?></p></li>
        <?php } ?>
    </ul>
<?php } ?>

<?php function drawSuccessMsg(array $messages): void
{ ?>
    <ul id='success'>
        <?php foreach($messages as $message){ ?>
            <li><p><?=$message?></p></li>
        <?php } ?>
    </ul>
<?php } ?>