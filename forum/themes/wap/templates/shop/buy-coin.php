<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php elseif ($success): ?>
    <div class="gmenu"><?=$success?></div>
<?php else: ?>
    <div class="notif"><?=$buyCoinHelp?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" class="menu">
    <h3>Vàng</h3>
    <div><input type="number" min="1" step="1" name="gold" value="<?=$input_gold?>" required="required" /> * 100</div>
    <input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div>