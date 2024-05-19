<?php 

declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
require_once(__DIR__ . '/../utils/Request.php');

?>

<?php function drawWishListForm(Session $session): void
{ ?>
    <aside id="related">
        <button class="close-sidebar">
            <img src="../assets/img/close.svg">
        </button>
        <div class="aside-box">
            <h2>Share your wishlist!</h2>
            <form action="../actions/action_send_wishlist.php" method="post" id="wishlist-form">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis ex at nunc interdum, vel.</p>
                <label>
                    Email <input type="email" name="email" required placeholder="Enter email">
                </label>
                <label>
                    Your Message <textarea id='expanding-textarea' name="wishlist-message" rows="1" placeholder="Add a personal message (optional)"></textarea>
                </label>

                <?php drawErrors($session->getErrorMessages()) ?>
                <?php drawSuccessMsg($session->getSuccessMessages()) ?>

                <div class='buttons'>
                    <?= Request::generateCsrfTokenInput() ?>
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let textarea = document.getElementById('expanding-textarea'); // TODO id?

            textarea.addEventListener('input', function() {
                this.style.height = 'auto'; 
                this.style.height = (this.scrollHeight > 256 ? 256 : this.scrollHeight) + 'px';
            });
        });
    </script>
<?php } ?>
