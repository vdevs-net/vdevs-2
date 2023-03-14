<?php
    $this->layout('layout');
?>
<?php if ($alert): ?>
    <div class="notif"><?=$alert?></div>
<?php endif ?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>

<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p>
            <h3><?=$lang['login']?></h3>
            <div><input type="text" name="account" maxlength="30" value="<?=$input_account?>"<?php if ($error_account): ?> style="background-color: #FFCCCC"<?php endif ?> autocomplete="off"/></div>
            <small><?=$lang['login_help']?></small>
        </p>
        <p>
            <h3><?=$lang['password']?></h3>
            <div><input type="password" name="password" maxlength="32" value="<?=$input_password?>"<?php if ($error_password): ?> style="background-color: #FFCCCC"<?php endif ?> /></div>
            <small><?=$lang['password_help']?></small>
        </p>
        <p>
            <h3>Nhập lại mật khẩu</h3>
            <div><input type="password" name="cf_password" maxlength="32" value="<?=$input_cf_password?>"<?php if ($error_cf_password): ?> style="background-color: #FFCCCC"<?php endif ?> /></div>
        </p>
        <p>
            <h3><?=$lang['name']?></h3>
            <div><input type="text" name="imname" maxlength="30" value="<?=$input_name?>"<?php if ($error_name): ?> style="background-color: #FFCCCC"<?php endif ?> /></div>
            <small><?=$lang['name_help']?></small>
        </p>
        <p>
            <h3><?=$lang['gender']?></h3>
            <select name="sex"<?php if ($error_sex): ?> style="background-color: #FFCCCC"<?php endif ?>>
                <option value="?">-?-</option>
                <option value="m"<?php if($input_sex == 'm'): ?> selected="selected"<?php endif?>><?=$lang['sex_m']?></option>
                <option value="f"<?php if($input_sex == 'f'): ?> selected="selected"<?php endif?>><?=$lang['sex_w']?></option>
            </select>
        </p>
        <p>
            <h3><?=$lang['about']?></h3>
            <div><textarea rows="3" name="about"><?=$input_about?></textarea></div>
            <small><?=$lang['about_help']?></small>
        </p>
    </div>
    <div class="menu">
        <p>
            <h3><?=$lang['captcha']?></h3>
            <div><img src="<?=$captcha_src?>" alt="<?=$lang['captcha']?>" border="1" /></div>
            <div><input type="text" size="5" maxlength="5"  name="captcha"<?php if ($error_captcha): ?> style="background-color: #FFCCCC"<?php endif ?> /></div>
            <small><?=$lang['captcha_help']?></small>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['registration']?>"/></p>
    </div>
</form>
<div class="notif"><small><?=$lang['registration_terms']?></small></div>