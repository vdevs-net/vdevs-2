<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php elseif ($success): ?>
    <div class="gmenu"><?=$success?></div>
<?php else: ?>
    <div class="notif"><?=$sendCoinHelp?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" class="menu">
    <div><h3>Tên người nhận</h3><input type="text" name="name" value="<?=$input_name?>" required="required" /></div>
    <div><h3>Số xu chuyển</h3><input type="number" min="100" step="10" name="coin" value="<?=$input_coin?>" required="required" /></div>
    <div><p><input type="submit" name="submit" value="Xác nhận" /></p></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>