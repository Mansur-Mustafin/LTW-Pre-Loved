<?php

declare(strict_types=1);

require_once(__DIR__ . '/../core/User.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../utils/Request.php');
?>


<?php function drawProfile(User $user, Session $session,bool $isCurrentUser): void
{?>    
    <p class="hidden" id="session_id"><?= $session->getId()?></p>
    <aside id="profile-info">
        <img src="<?=htmlspecialchars($user->image_path)?>" alt="Account Profile Picture">

        <div class="user-info">
            <p class="user-name"><?=htmlspecialchars($user->username)?></p>
            <p class="user-email"><?=htmlspecialchars($user->email)?></p>
            <p class="user-number"><?=htmlspecialchars($user->phonenumber)?></p>
            <p class="user-address"><?= htmlspecialchars($user->address) ?> </p>
        </div>

        <?php if ($isCurrentUser) { ?>
            <form id="enable-editing-mode" method="get">
                <div class='buttons'>
                    <button name="action" value="profile">Edit Profile</button>
                    <button name="action" value="password">Change Password</button>
                </div>
            </form>
            <nav class="pages">
                <ul>
                    <li class="filter-element">
                        <a href="../pages/profile.php">
                            <h4 class="hover-element">
                                Are selling
                            </h4>
                        </a>
                    </li>

                    <li class="filter-element">
                        <a href="../pages/profile.php?action=transactions">
                            <h4 class="hover-element">
                                Bought
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element">
                        <a href="../pages/dashboard.php">
                            <h4 class="hover-element">
                                Dashboard
                            </h4>
                        </a>
                    </li>
                    <li class="filter-element">
                        <a href="../pages/profile.php?action=sold">
                            <h4 class="hover-element">
                                Sold
                            </h4>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php } ?>

        <?php drawErrors($session->getErrorMessages()) ?>
    </aside>
   
<?php } ?>

<?php function drawEditProfile(User $user): void
{?>    
    <aside id="profile-info">
        <img id="current_profile_img" src="<?=htmlspecialchars($user->image_path)?>" alt="Account Profile Picture">
        <form id="edit-profile-info" action="../actions/action_update_user.php" method="post" enctype="multipart/form-data">
            <?= Request::generateCsrfTokenInput() ?>
            <label><img src="../assets/img/edit.svg" alt="Edit icon">
                <input id="profile_img" name="profile_img" type="file" accept="image/png,image/jpeg">
            </label>

            <label>
                <input type="text" name="new_username" placeholder= "Username" value='<?=htmlspecialchars($user->username)?>'>
            </label>
            <label>
                <input type="text" name="new_email" placeholder= "Email" value='<?=htmlspecialchars($user->email)?>'>
            </label>
            <label>
                <input type="tel" name="new_phonenumber" placeholder= 'Phone number' value='<?=htmlspecialchars($user->phonenumber)?>'>
            </label>
            <label>
                <input type="text" name="new_address" placeholder= "Address" value='<?= htmlspecialchars($user->address)?>' >
            </label>
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


<?php function drawChangePassword(User $user): void
{?>
    <aside id="profile-info">
        <img id="current_profile_img" src="<?=htmlspecialchars($user->image_path)?>" alt="Account Profile Picture">
        <form id="edit-profile-info" action="../actions/action_update_user.php" method="post" enctype="multipart/form-data">
            <?= Request::generateCsrfTokenInput() ?>
            <label>
                <input type="password" name="new_password" placeholder="New Password">
            </label>
            <label>
                <input type="password" name="new_password_confirmation" placeholder="Confirm New Password">
            </label>
            <label>
                <input type="password" name="old_password" placeholder="Old Password">
            </label>

            <div class='buttons'>
                <button type="submit" name="change-password" value="save">Save</button>
                <button type="submit" name="change-password" value="cancel">Cancel</button>
            </div>
        </form>
    </aside>
<?php } ?>
