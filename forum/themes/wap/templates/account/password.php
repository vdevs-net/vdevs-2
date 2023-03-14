<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p>
            <b><?=$lang['password']?></b><br />
            <input type="password" name="oldpass" />
        </p>
        <p>
            <b><?=$lang['input_new_password']?></b><br />
            <input type="password" name="newpass" />
        <p>
            <b><?=$lang['repeat_password']?></b><br />
            <input type="password" name="newconf" />
        </p>
        <p><input type="submit" value="<?=$lang['save']?>" name="submit" /></p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>