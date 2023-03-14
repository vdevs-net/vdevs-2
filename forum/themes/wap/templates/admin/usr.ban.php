<?php
    $this->layout('layout');
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$lang['ban_do']?></div>
<?php if ($success): ?>
    <div class="gmenu"><?=$lang['user_banned']?></div>
<?php else: ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
<form action="<?=$formAction?>" method="post">
    <div class="menu">
        <h3><?=$lang['ban_type']?></h3>
<?php if ($rights >= 6): ?>
        <div><input name="term" type="radio" value="1" checked="checked" /> <?=$lang['ban_1']?></div>
        <div><input name="term" type="radio" value="3" /> <?=$lang['ban_3']?></div>
        <div><input name="term" type="radio" value="10" /> <?=$lang['ban_10']?></div>
<?php endif ?>
<?php if ($rights == 3 || $rights >= 6): ?>
        <div><input name="term" type="radio" value="11"<?php if ($rights == 3): ?> checked="checked"<?php endif ?> /> <?=$lang['ban_11']?></div>
<?php endif ?>
<?php if ($rights == 2 || $rights >= 6): ?>
        <div><input name="term" type="radio" value="12"<?php if ($rights == 2): ?> checked="checked"<?php endif ?> /> <?=$lang['ban_12']?></div>
<?php endif ?>
        <h3><?=$lang['ban_time']?></h3>
        <div><input type="text" name="timeval" size="2" maxlength="2" value="12"/> <?=$lang['time']?></div>
        <div><input name="time" type="radio" value="1" /> <?=$lang['ban_time_minutes']?></div>
        <div><input name="time" type="radio" value="2" checked="checked" /> <?=$lang['ban_time_hours']?></div>
<?php if ($rights >= 6): ?>
        <div><input name="time" type="radio" value="3" /> <?=$lang['ban_time_days']?></div>
<?php endif ?>
<?php if ($rights >= 7): ?>
        <input name="time" type="radio" value="4" /> <span class="red"><?=$lang['ban_time_before_cancel']?></span>
<?php endif ?>
        <h3><?=$lang['reason']?></h3>
        <div><textarea rows="<?=$user['field_h']?>" name="reason"></textarea></div>
        <p><input type="submit" value="<?=$lang['ban_do']?>" name="submit" /></p>
    </div>
</form>
<?php endif ?>