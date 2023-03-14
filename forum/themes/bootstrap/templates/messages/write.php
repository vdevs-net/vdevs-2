<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
<?php if ($can_write): ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif ?>
    <form name="form" action="<?=$form_action?>" method="post"  enctype="multipart/form-data" class="form-horizontal">
        <div class="form-group">
            <label for="nick" class="control-label col-sm-3"><?=$lang['to_whom']?></label>
            <div class="col-sm-9">
            <?php if ($require_name): ?>
                <input type="text" name="nick" maxlength="30" value="<?=$user_name?>" placeholder="<?=$lang['to_whom']?>" class="form-control" id="nick" />
            <?php else: ?>
                <p class="form-control-static"><a href="<?=$user_profile_url?>"><b><?=$user_name?></b></a></p>
            <?php endif ?>
            </div>
        </div>
        <div class="form-group">
            <label for="message" class="control-label col-sm-3"><?=$lang['message']?></label>
            <div class="col-sm-9">
                <?=$bbcode_editor?>
                <p><textarea rows="<?=$user['field_h']?>" name="text" class="form-control" id="message"><?=$input_message?></textarea></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="file" name="fail" class="form-control" />
            </div>
        </div>
        <hr />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="submit" name="submit" value="<?=$lang['sent']?>" class="btn btn-primary"/>
            </div>
        </div>
    </form>
<?php else: ?>
    <?=$lang['access_forbidden']?>
<?php endif ?>
</div>
</div>