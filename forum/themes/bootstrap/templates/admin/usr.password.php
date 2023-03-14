<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$lang['change_password']?></div>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<form action="<?=$formAction?>" method="post">
    <div class="gmenu">
        <p><?=$lang['input_new_password']?>:<br /><input type="password" name="newpass" /></p>
        <p><?=$lang['repeat_password']?>:<br /><input type="password" name="newconf" /></p>
        <p><input type="submit" value="<?=$lang['save']?>" name="submit" /></p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
<div class="notif"><?=$lang['password_change_help']?></div>