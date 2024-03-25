<? php
    declare(strict_types=1);
    require_once(__DIR__.'/../core/user.class.php');
?>

<?php function drawEditProfile(User $user) {?>
    <div id="profile-display">
        <div class="profile-info">
            <img id="current_profile_img" src=<?=htmlspecialchars($user->image_path)?> alt="Account Profile Picture">
            <form id="edit-profile-info" action="../actions/action_update_profile.php" method="post" enctype="multipart/form-data">
                <label><img src="../assets/img/edit.svg" alt="Edit icon">
                    <input id="profile_img" name="profile_img" type="file" accept="image/png,image/jpeg">
                </label>

                <input type="text" name="new_username" placeholder= "Username" value=<?=$user->username?>>
                <input type="text" name="new_email" placeholder= "Email" value=<?=$user->email?>>
                <input type="text" name="new_phonenumber" placeholder= 'Phone number' value=<?=$user->phonenumber?>>
                <input type="text" name="new_address" placeholder= "Address" value=<?=$user->address?>>
                <input type="password" name="new_password" placeholder="Password">
                <!-- TODO <input type="password" name="new_password_confirm" placeholder="Confirm Password"> -->
                <div class='buttons'>
                    <button type="submit" name="edit" value="save">Save</button>
                    <button type="submit" name="edit" value="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById("profile_img").onchange = function() {
            document.getElementById("current_profile_img").src = URL.createObjectURL(profile_img.files[0])
        }
    </script>
<?php } ?>