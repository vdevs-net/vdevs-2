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
<form action="<?=$form_action?>" method="post" class="form-horizontal noPusher">
    <div class="media">
        <div class="pull-left" style="width: 100px; height: 100px">
            <img class="media-object" src="<?=$fb_avatar_src?>" alt="<?=$fb_name?>" />
        </div>
        <div class="media-body">
            <h4 class="media-heading"><?=$fb_name?></h4>
            <p>Email: <b><?=$fb_email?></b></p>
        </div>
    </div>
    <hr />
    <div class="form-group">
        <label for="account" class="control-label col-sm-3">Chọn tên tài khoản</label>
        <div class="col-sm-9">
            <input type="text" name="account" maxlength="32" value="<?=$input_username?>" autocomplete="off" class="form-control" id="account" />
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label col-sm-3">Mật khẩu</label>
        <div class="col-sm-9">
            <input type="password" name="password" maxlength="32" class="form-control" id="password" />
        </div>
    </div>
    <div class="form-group">
        <label for="cf_password" class="control-label col-sm-3">Nhập lại mật khẩu</label>
        <div class="col-sm-9">
            <input type="password" name="cf_password" maxlength="32" class="form-control" id="cf_password" />
        </div>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="Đăng nhập" class="btn btn-primary" /></div>
    </div>
</form>
</div>
</div>