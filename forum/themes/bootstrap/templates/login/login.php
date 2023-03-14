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
    <div class="form-group">
        <label for="account" class="control-label col-sm-3"><?=$lang['login_name']?></label>
        <div class="col-sm-9">
            <input type="text" name="account" value="<?=$input_account?>" maxlength="30" class="form-control" id="account" />
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="control-label col-sm-3"><?=$lang['password']?></label>
        <div class="col-sm-9">
            <input type="password" name="password" maxlength="32" class="form-control" id="password" />
        </div>
    </div>
    <?php if($show_captcha): ?>
    <div class="form-group">
        <label for="captcha" class="control-label col-sm-3"><?=$lang['verifying_code']?></label>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6"><input type="text" size="5" maxlength="5"  name="code" class="form-control" id="captcha" /></div>
                <div class="col-sm-6 xs-margin-top"><img src="<?=$captcha_url?>" alt="<?=$lang['verifying_code']?>"/></div>
            </div>
        </div>
    </div>
    <?php endif ?>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <div class="checkbox"><label><input type="checkbox" name="mem" value="1" checked="checked"/><?=$lang['remember']?></label></div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <input type="submit" name="submit" value="<?=$lang['login']?>" class="btn btn-primary" />
            <a href="<?=$site_path?>/login/facebook" class="btn btn-social btn-facebook margin-left noPusher"><span class="fa fa-facebook"></span> Login with Facebook</a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3"><a href="<?=$site_path?>/account/recover" title="<?=$lang['forgotten_password']?>"><?=$lang['forgotten_password']?></a></div>
    </div>
</form>
</div>
</div>
