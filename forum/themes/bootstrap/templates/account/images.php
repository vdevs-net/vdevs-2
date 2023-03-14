<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
<form enctype="multipart/form-data" method="post" action="<?=$form_action?>" class="form-horizontal">
    <div class="form-group">
        <label for="file" class="col-sm-3 control-label"><?=$lang['select_image']?></label>
        <div class="col-sm-9">
            <input type="file" name="imagefile" accept="image/*" class="form-control" id="file" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox"><label><input type="checkbox" name="delete" value="1" /> <?=$lang_delete?></label></div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <input type="submit" name="submit" value="<?=$lang['upload']?>" class="btn btn-primary" />
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
<div class="alert alert-info"><?=$form_help?></div>
</div>
</div>