<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/user.class.php');
?>


<?php function drawProfile(User $user) {?>
    
    <div id="profile-display">
        <div class="profile-info">
            <img src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
            <form id="profile-upload-form" action="../actions/action_upload_profile_foto.php" method="post" enctype="multipart/form-data">
                <label><img src="../assets/img/edit.svg" alt="Edit icon">
                    <!-- TODO change this later -->
                    <input type="file" name="profile_img" accept="image/png,image/jpeg" onchange="document.getElementById('profile-upload-form').submit(); console.log('Hello');"> 
                    
                </label>
            </form>
            <p class="user-name"><?=htmlspecialchars($user->username)?></p>
            <p class="user-email"><?=htmlspecialchars($user->email)?></p>
            <p class="user-number"><?=htmlspecialchars($user->phonenumber)?></p>
        </div>
    </div>
    
<?php } ?>
