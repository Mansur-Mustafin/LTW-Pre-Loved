<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/Session.php');
?>


<?php function drawLoginForm(Session $session) 
{?>
    <section id="login-register">

        <input type="checkbox" id="login-tuggle-checked" checked>
        <input type="checkbox" id="register-tuggle-checked">
        <button id="login-tuggle">Login</button>
        <button id="register-tuggle">Create Account</button>
        <form id="login-form">
            <label>
                Username <input type="text" name="email" required>
            </label>
            <label>
                Password <input type="password" name="password" required>
            </label>
            <?php drawErrors($session->getErrorMessages()) ?>
            <button formaction="../actions/action_login.php" formmethod="post">Login</button>
        </form>
        <form id="register-form">
            <label>
                Email <input type="email" name="email" required>
            </label>
            <label>
                Username <input type="text" name="username" required>
            </label>
            <label>
                Password <input type="password" name="password" required>
            </label>
            <label>
                Confirm Password <input type="password" name="confirm_password" required>
            </label>
            <label>
                Address <input type="text" name="address">
            </label>
            <label>
                Phone Number <input type="tel" name="phonenumber">
            </label>
            <button formaction="../actions/action_register.php" formmethod="post">Register</button>
        </form>


    </section>

    <?php // TODO mover isso depois ?>
    
    <script>
        document.getElementById('login-tuggle').addEventListener('click', function() {
            document.getElementById('login-tuggle-checked').checked = true;
            document.getElementById('register-tuggle-checked').checked = false;
        });

        document.getElementById('register-tuggle').addEventListener('click', function() {
            document.getElementById('register-tuggle-checked').checked = true;
            document.getElementById('login-tuggle-checked').checked = false;
        });
    </script>

<?php } ?>
