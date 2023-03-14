<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<div class="menu">
    <form action="<?=$form_action?>" method="post">
        <p>
            <b><?=$lang['your_login']?></b><br/>
            <input type="text" name="nick" />
        </p>
        <p>
            <b><?=$lang['your_email']?></b><br/>
            <input type="text" name="email" />
        </p>
        <p>
            <b><?=$lang['captcha']?></b><br/>
            <img src="<?=$captcha_src?>" alt="<?=$lang['captcha']?>"/><br />
            <input type="text" size="5" maxlength="5"  name="code" />
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['sent']?>"/></p>
    </form>
</div>
<div class="notif"><small><?=$lang['restore_help']?></small></div>