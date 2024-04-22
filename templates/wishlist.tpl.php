<?php 
declare(strict_types=1);

require_once(__DIR__.'/../utils/session.php')
?>

<?php function drawWishListForm(Session $session){ ?>
    <aside id="related">
        <h2>Share your wishlist!</h2>
        <form action="../actions/action_send_wishlist.php" method="post" id="wishlist-form">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis ex at nunc interdum, vel.</p>
            <label>
                Email <input type="email" name="email" required placeholder="Enter email">
            </label>
            <label>
                Your Message <textarea id='expanding-textarea' name="wishlist-message" rows="1" placeholder="Add a personal message (optional)"></textarea>
            </label>
            <?=drawErrors($session->getErrorMessages())?>
            <?=drawSuccessMsg($session->getSuccesMessages())?>
            <button type="submit">Send</button>
        </form>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var textarea = document.getElementById('expanding-textarea'); // TODO id? 

            textarea.addEventListener('input', function() {
                this.style.height = 'auto'; 
                this.style.height = (this.scrollHeight > 256 ? 256 : this.scrollHeight) + 'px';
            });
        });
    </script>
<?php } ?>
