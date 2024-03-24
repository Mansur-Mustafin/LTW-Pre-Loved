<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/user.class.php');
?>


<?php function drawProfile(User $user) {?>
    
    <div id="profile-display">
        <div class="profile-info">
            <img src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
            <p class="user-name"><?=htmlspecialchars($user->username)?></p>
            <p class="user-email"><?=htmlspecialchars($user->email)?></p>
            <p class="user-number"><?=htmlspecialchars($user->phonenumber)?></p>
            <p class="user-address"><?= htmlspecialchars($user->address) ?> </p>
            <form id="enable-editing-mode" method="post">
                <button name="edit" value="true">Edit</button>
            </form>
        </div>
    </div>
    
<?php } ?>
