<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<form action="ipban?mod=new" method="post">
<div class="menu">
    <p><h3><?=$lang['ip_address']?>:</h3><input type="text" name="ip" autocomplete="off" /></p>
    <p><h3><?=$lang['ban_type']?>:</h3>
        <input name="term" type="radio" value="1" checked="checked" /><?=$lang['blocking']?><br />
        <input name="term" type="radio" value="2" /><?=$lang['registration']?>
    </p>
    <p><h3><?=$lang['reason']?></h3>
        <textarea rows="<?=$user['field_h']?>" name="reason"></textarea><br />
        <small><?=$lang['not_mandatory_field']?></small>
    </p>
    <p><input type="submit" name="submit" value=" <?=$lang['ban_do']?> "/>&nbsp;<a href="ipban" class="btn"><?=$lang['cancel']?></a></p>
</div>
<div class="notif"><?=$lang['ip_ban_help']?></div>
</form>