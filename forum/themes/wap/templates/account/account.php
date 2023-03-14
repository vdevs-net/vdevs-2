<?php
    $this->layout('layout');
?>
<div class="menu"><a href="settings"><?=$lang['settings']?></a></div>
<div class="menu"><a href="password"><?=$lang['change_password']?></a></div>
<div class="menu"><a href="recover"><?=$lang['forgotten_password']?></a></div>
<div class="menu"><a href="ip"><?=$lang['ip_history']?></a></div>
<div class="menu"><a href="cover">Đổi ảnh bìa</a></div>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p><b><?=$lang['status']?></b> (<?=$lang['status_lenght']?>)<br /><input type="text" value="<?=$user_status?>" name="status" /></p>
        <p><b><?=$lang['avatar']?></b><br /><img src="<?=$user_avatar?>" width="32" height="32" alt="<?=$user_account?>" /><br /><small><a href="<?=$edit_avatar_url?>"><?=$lang['upload']?></a></small></p>
    </div>
    <div class="menu">
        <p>
            <h3><?=$lang['personal_data']?></h3>
            <b><?=$lang['name']?></b><br />
            <input type="text" name="imname" maxlength="32" value="<?=$user_name?>" />
        </p>
        <p>
            <b><?=$lang['specify_sex']?></b><br />
            <input type="radio" value="m" name="sex"<?php if($user_sex == 'm'): ?> checked="checked"<?php endif ?> /> <?=$lang['sex_m']?><br /> <input type="radio" value="f" name="sex"<?php if($user_sex == 'f'): ?> checked="checked"<?php endif ?> /> <?=$lang['sex_w']?>
        </p>
        <p>
            <b><?=$lang['birth_date']?></b><br />
            <input type="text" value="<?=$user_dayb?>" size="2" maxlength="2" name="dayb" />.<input type="text" value="<?=$user_monthb?>" size="2" maxlength="2" name="monthb" />.<input type="text" value="<?=$user_yearb?>" size="4" maxlength="4" name="yearb" />
        </p>
        <p><b><?=$lang['city']?></b><br /><input type="text" value="<?=$user_live?>" name="live" /></p>
        <p><b><?=$lang['about']?></b><br /><textarea rows="<?=$user['field_h']?>" name="about"><?=$user_about?></textarea></p>
    </div>
    <div class="menu">
        <p>
            <h3><?=$lang['communication']?></h3>
            <b><?=$lang['phone_number']?></b>:<br /><input type="text" value="<?=$user_mobile?>" disabled="disabled" />
        </p>
        <p><b>E-mail</b><br />
            <small><?=$lang['email_warning']?></small><br />
            <input type="text" value="<?=$user_mail?>" name="mail" /><br />
            <input name="mailvis" type="checkbox" value="1"<?=$user_mailvis?> /> <?=$lang['show_in_profile']?>
        </p>
        <p>
            <b>Mật khẩu hiện tại</b><br/><input type="password" name="password" /><br/>Cần nhập nếu bạn muốn thay đổi email!
        </p>
        <p><b>Facebook</b><br /><input type="text" value="<?=$user_facebook?>" name="facebook" /></p>
    </div>
    <div class="gmenu"><input type="submit" value="<?=$lang['save']?>" name="submit" /></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
