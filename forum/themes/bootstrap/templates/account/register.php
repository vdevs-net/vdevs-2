<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<?php if ($alert): ?>
    <div class="alert alert-warning"><?=$alert?></div>
<?php endif ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php endif ?>

<form action="<?=$form_action?>" method="post" class="form-horizontal">
    <div class="form-group<?php if ($error_account): ?> has-error<?php endif ?>">
        <label for="account" class="col-sm-3 control-label"><?=$lang['login']?></label>
        <div class="col-sm-9">
            <input type="text" name="account" maxlength="30" value="<?=$input_account?>" autocomplete="off" class="form-control" id="account" />
            <span class="help-block"><?=$lang['login_help']?></span>
        </div>
    </div>
    <div class="form-group<?php if ($error_password): ?> has-error<?php endif ?>">
        <label for="password" class="col-sm-3 control-label"><?=$lang['password']?></label>
        <div class="col-sm-9">
            <input type="password" name="password" maxlength="32" value="<?=$input_password?>" class="form-control" id="password" />
            <span class="help-block"><?=$lang['password_help']?></span>
        </div>
    </div>
    <div class="form-group<?php if ($error_cf_password): ?> has-error<?php endif ?>">
        <label for="cf_password" class="col-sm-3 control-label">Nhập lại mật khẩu</label>
        <div class="col-sm-9">
            <input type="password" name="cf_password" maxlength="32" value="<?=$input_cf_password?>" class="form-control" id="cf_password" />
        </div>
    </div>
    <hr />
    <div class="form-group<?php if ($error_name): ?> has-error<?php endif ?>">
        <label for="name" class="col-sm-3 control-label"><?=$lang['name']?></label>
        <div class="col-sm-9">
            <input type="text" name="imname" maxlength="30" value="<?=$input_name?>" class="form-control" id="name" />
            <span class="help-block"><?=$lang['name_help']?></span>
        </div>
    </div>
    <div class="form-group<?php if ($error_sex): ?> has-error<?php endif ?>">
        <label for="gender" class="col-sm-3 control-label"><?=$lang['gender']?></label>
        <div class="col-sm-9">
            <select name="sex" class="form-control" id="gender">
                <option value="?">-?-</option>
                <option value="m"<?php if($input_sex == 'm'): ?> selected="selected"<?php endif?>><?=$lang['sex_m']?></option>
                <option value="f"<?php if($input_sex == 'f'): ?> selected="selected"<?php endif?>><?=$lang['sex_w']?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="about" class="col-sm-3 control-label"><?=$lang['about']?></label>
        <div class="col-sm-9">
            <textarea rows="3" name="about" class="form-control" id="about"><?=$input_about?></textarea>
            <span class="help-block"><?=$lang['about_help']?></span>
        </div>
    </div>
    <hr />
    <div class="form-group<?php if ($error_captcha): ?> has-error<?php endif ?>">
        <label for="captcha" class="col-sm-3 control-label"><?=$lang['captcha']?></label>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6"><input type="text" maxlength="5"  name="captcha" class="form-control" id="captcha" /></div>
                <div class="col-sm-6 xs-margin-top"><img src="<?=$captcha_src?>" alt="<?=$lang['captcha']?>" border="1" /></div>
            </div>
            <span class="help-block"><?=$lang['captcha_help']?></span>
        </div>
    </div>
    <hr />
    <div class="alert alert-info"><?=$lang['registration_terms']?></div>
    <input type="submit" name="submit" value="<?=$lang['registration']?>" class="btn btn-primary btn-block" />
    </div>
</form>
</div></div>