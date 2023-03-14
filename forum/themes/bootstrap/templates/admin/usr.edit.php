<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$lang['edit']?></div>
<?php if ($result): ?>
    <div class="gmenu"><?=$result?></div>
<?php endif ?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<form action="<?=$formAction?>" method="post">
    <div class="menu">
        <p><?=$lang['status']?>: (<?=$lang['status_lenght']?>)<br /><input type="text" value="<?=$thisUser['status']?>" name="status" /></p>
<?php if ($thisUser['hasAvatar']): ?>
        <p><?=$lang['avatar']?>:<br /><img src="<?=$thisUser['avatar']?>" width="32" height="32" alt="<?=$thisUser['account']?>" /><br /><small><a href="<?=$thisUser['delAvatarUrl']?>"><?=$lang['delete']?></a></small></p>
<?php endif ?>
<?php if ($thisUser['hasCover']): ?>
        <p><?=$lang['photo']?>:<br /><img src="<?=$thisUser['cover']?>" alt="<?=$thisUser['account']?>" border="0" /><br /><small><a href="<?=$thisUser['delCoverUrl']?>"><?=$lang['delete']?></a></small></p>
<?php endif ?>
    </div>
    <div class="menu">
        <h3><?=$lang['personal_data']?></h3>
        <p><?=$lang['name']?>:<br /><input type="text" value="<?=$thisUser['name']?>" disabled="disabled" /></p>
        <p><?=$lang['city']?>:<br /><input type="text" value="<?=$thisUser['live']?>" name="live" /></p>
        <p><?=$lang['about']?>:<br /><textarea rows="<?=$user['field_h']?>" name="about"><?=$thisUser['about']?></textarea></p>
        <h3><?=$lang['communication']?></h3>
        <p><?=$lang['phone_number']?>:<br /><input type="text" value="<?=$thisUser['mobile']?>" disabled="disabled" /></p>
        <p>E-mail:<br /><input type="text" value="<?=$thisUser['email']?>" name="mail" /></p>
        <p>Facebook:<br /><input type="text" value="<?=$thisUser['facebook']?>" name="facebook" /></p>
    </div>
    <div class="menu">
        <h3><?=$lang['rank']?></h3>
        <p><ul>
            <li><input type="radio" value="0" name="rights"<?php if (!$thisUser['rights']): ?> checked="checked"<?php endif ?> /> <b><?=$lang['rank_0']?></b></li>
            <li><input type="radio" value="3" name="rights"<?php if ($thisUser['rights'] == 3): ?> checked="checked"<?php endif ?> /> <?=$lang['rank_3']?></li>
            <li><input type="radio" value="6" name="rights"<?php if ($thisUser['rights'] == 6): ?> checked="checked"<?php endif ?> /> <?=$lang['rank_6']?></li>
<?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
            <li><input type="radio" value="7" name="rights"<?php if ($thisUser['rights'] == 7): ?> checked="checked"<?php endif ?> /> <?=$lang['rank_7']?></li>
            <li><input type="radio" value="9" name="rights"<?php if ($thisUser['rights'] == 9): ?> checked="checked"<?php endif ?> /> <span class="red"><b><?=$lang['rank_9']?></b></span></li>
<?php endif ?>
        </ul></p>
    </div>
    <div class="gmenu"><input type="submit" value="<?=$lang['save']?>" name="submit" /></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>