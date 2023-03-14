<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php endif ?>
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="nick" class="col-sm-3 control-label"><?=$lang['your_login']?></label>
            <div class="col-sm-9">
                <input type="text" name="nick" id="nick" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-3 control-label"><?=$lang['your_email']?></b></label>
            <div class="col-sm-9">
                <input type="text" name="email" id="email" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label for="captcha" class="col-sm-3 control-label"><?=$lang['captcha']?></label>
            <div class="col-sm-9 row">
                <div class="col-sm-6"><input type="text" maxlength="5" name="code" id="captcha" class="form-control" /></div>
                <div class="col-sm-6 xs-margin-top"><img src="<?=$captcha_src?>" alt="<?=$lang['captcha']?>" /></div>
            </div>
        </div>
        <hr />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="submit" name="submit" value="<?=$lang['sent']?>" class="btn btn-primary" />
            </div>
        </div>
    </form>
<div class="alert alert-info"><?=$lang['restore_help']?></div>
</div></div>