<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<div class="menu">
    <form action="<?=$form_action?>" method="post">
        <p>
            <h3><?=$lang['login_name']?></h3>
            <input type="text" name="account" value="<?=$input_account?>" maxlength="20" />
            <h3><?=$lang['password']?></h3>
            <input type="password" name="password" maxlength="20" />
        </p>
        <?php if($show_captcha): ?>
            <p><?=$lang['verifying_code']?><br/><img src="<?=$captcha_url?>" alt="<?=$lang['verifying_code']?>"/><br/><input type="text" size="5" maxlength="5"  name="code" /></p>
        <?php endif ?>
        <p><input type="checkbox" name="mem" value="1" checked="checked"/><?=$lang['remember']?></p>
        <p><input type="submit" name="submit" value="<?=$lang['login']?>"/></p>
    </form>
</div>