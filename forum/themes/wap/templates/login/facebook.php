<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <div><center><img src="<?=$fb_avatar_src?>" alt="<?=$fb_name?>" /></center></div>
        <div>Tài khoản Facebook: <b><?=$fb_name?></b></div>
        <div>Email: <b><?=$fb_email?></b></div>
    </div>
    <div class="menu">
        <h3>Chọn tên tài khoản</h3>
        <input type="text" name="account" maxlength="32" value="<?=$input_username?>" autocomplete="off" />
        <h3>Mật khẩu</h3>
        <input type="password" name="password" maxlength="32" />
        <h3>Nhập lại mật khẩu</h3>
        <input type="password" name="cf_password" maxlength="32" />
    </div>
    <div class="menu"><input type="submit" name="submit" value="Đăng nhập" /></div>
</form>