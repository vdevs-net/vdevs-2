<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php elseif ($success): ?>
    <div class="alert alert-success"><?=$success?></div>
<?php else: ?>
    <div class="alert alert-info"><?=$buyCoinHelp?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" class="form-inline">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">Vàng</div>
            <input type="number" class="form-control" min="1" step="1" name="gold" value="<?=$input_gold?>" required="required" />
            <span class="input-group-addon">00</span>
        </div>
    </div>
    <input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div></div>