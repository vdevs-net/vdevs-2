<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif ?>
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="password" class="col-sm-3 control-label"><?=$lang['password']?></label>
            <div class="col-sm-9">
                <input type="password" name="oldpass" class="form-control" id="password" />
            </div>
        </div>
        <div class="form-group">
            <label for="newpass" class="col-sm-3 control-label"><?=$lang['input_new_password']?></label>
            <div class="col-sm-9">
                <input type="password" name="newpass" class="form-control" id="newpass" />
            </div>
        </div>
        <div class="form-group">
            <label for="newconf" class="col-sm-3 control-label"><?=$lang['repeat_password']?></label>
            <div class="col-sm-9">
                <input type="password" name="newconf" class="form-control" id="newconf" />
            </div>
        </div>
        <hr class="separator" />
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" value="<?=$lang['save']?>" name="submit" class="btn btn-success" />
            </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>
</div>