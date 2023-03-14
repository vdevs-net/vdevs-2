<?php
    $this->layout('layout');
?>
<?php if ($offer): ?>
    <div class="notif"><?=$offer?></div>
<?php endif ?>
<div class="menu"><?=$description?></div>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<form action="<?=$form_action?>" class="menu" method="post">
    <div>
        <h3>Loại thẻ</h3>
        <select name="card_type"><?=$card_types_option?></select>
    </div>
    <div><h3>Mã thẻ</h3><input type="text" value="<?=$input_pin?>" name="pin" autocomplete="off" required="required" /></div>
    <div><h3>Số serial</h3><input type="text" value="<?=$input_seri?>" name="seri" autocomplete="off" required="required" /></div>
    <div><h3>Mã bảo mật</h3><div><input type="text" value="" name="code" maxlength="4" autocomplete="off" required="required" /><br/><img src="<?=$captcha_src?>"/></div></div>
    <div><br/><input type="submit" name="submit" value="Nạp thẻ"/></div>
</form>