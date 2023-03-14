<?php
    $this->layout('layout');
?>
<?php if ($can_write): ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <div class="menu">
        <form name="form" action="<?=$form_action?>" method="post"  enctype="multipart/form-data">
            <?php if ($require_name): ?>
                <p><input type="text" name="nick" maxlength="30" value="<?=$user_name?>" placeholder="<?=$lang['to_whom']?>"/></p>
            <?php else: ?>
                <p><?=$lang['to_whom']?>: <a href="<?=$user_profile_url?>"><b><?=$user_name?></a></b></p>
            <?php endif ?>
            <p><textarea rows="<?=$user['field_h']?>" name="text"><?=$input_message?></textarea></p>
            <p><input type="file" name="fail" style="width: 100%; max-width: 160px"/></p>
            <p><input type="submit" name="submit" value="<?=$lang['sent']?>"/></p>
        </form>
    </div>
<?php else: ?>
    <div class="rmenu"><?=$lang['access_forbidden']?></div>
<?php endif ?>