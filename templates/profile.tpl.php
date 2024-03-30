<?php

declare(strict_types=1);

require_once(__DIR__.'/../core/user.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
?>


<?php function drawProfile(User $user, Session $session) {?>
    
    <aside id="profile-info">
        <img src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
        <div class="user-info">
            <p class="user-name"><?=htmlspecialchars($user->username)?></p>
            <p class="user-email"><?=htmlspecialchars($user->email)?></p>
            <p class="user-number"><?=htmlspecialchars($user->phonenumber)?></p>
            <p class="user-address"><?= htmlspecialchars($user->address) ?> </p>
        </div>
        <form id="enable-editing-mode" method="post">
            <div class='buttons'>
                <button name="edit" value="profile">Edit Profile</button>
                <button name="edit" value="password">Change Password</button>
            </div>
        </form>
        <?=drawErrors($session->getErrorMessages())?>
    </aside>
   
<?php } ?>


<?php function drawEditProfile(User $user) {?>
    
    <aside id="profile-info">
        <img id="current_profile_img" src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
        <form id="edit-profile-info" action="../actions/action_update_user.php" method="post" enctype="multipart/form-data">
            <label><img src="../assets/img/edit.svg" alt="Edit icon">
                <input id="profile_img" name="profile_img" type="file" accept="image/png,image/jpeg">
            </label>

            <input type="text" name="new_username" placeholder= "Username" value='<?=htmlspecialchars($user->username)?>'>
            <input type="text" name="new_email" placeholder= "Email" value='<?=htmlspecialchars($user->email)?>'>
            <input type="text" name="new_phonenumber" placeholder= 'Phone number' value='<?=htmlspecialchars($user->phonenumber)?>'>
            <input type="text" name="new_address" placeholder= "Address" value='<?= htmlspecialchars($user->address)?>' >
            <div class='buttons'>
                <button type="submit" name="edit-profile" value="save">Save</button>
                <button type="submit" name="edit-profile" value="cancel">Cancel</button>
            </div>
        </form>
    </aside>
    
    <script type="text/javascript">
        document.getElementById("profile_img").onchange = function() {
            document.getElementById("current_profile_img").src = URL.createObjectURL(profile_img.files[0])
        }
    </script>
<?php } ?>


<?php function drawChangePassword(User $user) {?>
    
    <aside id="profile-info">
        <img id="current_profile_img" src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
        <form id="edit-profile-info" action="../actions/action_update_user.php" method="post" enctype="multipart/form-data">
            <input type="password" name="new_password" placeholder="New Password">
            <input type="password" name="new_password_confirmation" placeholder="Confirm New Password">
            <input type="password" name="old_password" placeholder="Old Password">

            <div class='buttons'>
                <button type="submit" name="change-password" value="save">Save</button>
                <button type="submit" name="change-password" value="cancel">Cancel</button>
            </div>
        </form>
    </aside>

<?php } ?>