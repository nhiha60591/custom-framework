<div id="content" class="col-xs-12 col-sm-10">
    <h1 class="page-title">Your Profile</h1>
    <div class="your-profile">
        <?php if( isset( $mes ) && !empty( $mes )) echo $mes; ?>
        <?php if( !empty( $user ) ): ?>
            <?php foreach( $user as $u ): ?>
                <form name="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?=$u->user_id; ?>">
                <p>
                    <label for="first-name">First name:</label>
                    <input type="text" name="first_name" id="first-name" class="first-name regular-text" value="<?=$u->first_name?>" />
                </p>
                <p>
                    <label for="last-name">Last name:</label>
                    <input type="text" name="last_name" id="last-name" class="last-name regular-text" value="<?=$u->last_name?>" />
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="first-name" class="email regular-text" value="<?=$u->email?>" />
                </p>
                <p>
                    <label for="website">Website:</label>
                    <input type="text" name="website" id="website" class="website regular-text" value="<?=$u->website?>" />
                </p>
                <p>
                    <label for="bio">Bio:</label>
                    <textarea name="bio" id="bio" class="bio regular-text" rows="6"><?=$u->bio?></textarea>
                </p>
                <p>
                    <label for="input-avatar">Avatar:</label>
                    <input style="display: inline;" type="file" name="avatar" id="input-avatar" class="input-avatar regular-text" />
                </p>
                <p>
                    <input type="submit" name="update" value="Update" />
                </p>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<!--End Content-->