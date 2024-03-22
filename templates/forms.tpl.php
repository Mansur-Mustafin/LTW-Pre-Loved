<?php
declare(strict_types=1);
?>


<?php function drawLoginForm() {?>
    <section id="login-register">


        <input type="checkbox" id="login-tuggle-checked" checked>
        <input type="checkbox" id="register-tuggle-checked">
        <button id="login-tuggle">Login</button>
        <button id="register-tuggle">Create Account</button>
        <form id="login-form">
            <label>
                Username <input type="text" name="username">
            </label>
            <label>
                Password <input type="password" name="password">
            </label>
            <button formaction="#" formmethod="post">Login</button>
        </form>
        <form id="register-form">
            <label>
                Username <input type="text" name="username">
            </label>
            <label>
                Password <input type="password" name="password">
            </label>
            <button formaction="#" formmethod="post">Register</button>
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
