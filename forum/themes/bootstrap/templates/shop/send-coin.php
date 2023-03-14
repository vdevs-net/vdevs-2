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
    <div class="alert alert-info"><?=$sendCoinHelp?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">Tên người nhận</label>
        <div class="col-sm-9">
            <input type="text" name="name" value="<?=$input_name?>" required="required" class="form-control" id="name" />
        </div>
    </div>
    <div class="form-group">
        <label for="coin" class="col-sm-3 control-label">Số xu chuyển</label>
        <div class="col-sm-9">
            <input type="number" min="100" step="10" name="coin" value="<?=$input_coin?>" required="required" class="form-control" id="coin" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <input type="submit" name="submit" value="Xác nhận" class="btn btn-primary" />
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div></div>