<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<form action="<?=$form_action?>" method="post" name="form">
<div class="panel panel-primary">
<div class="panel-heading"><?=$form_title?></div>
<div class="panel-body">
    <div class="form-group">
        <?=$bbcode_editor?>
        <p><textarea name="text" class="form-control" rows="2" required="required"><?=$input_text?></textarea></p>
    </div>
<?php if($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php endif ?>
</div>
<div class="panel-footer clearfix"><div class="pull-left"><select name="privacy" class="form-control"><?=$privacy_option?></select></div><div class="pull-right"><a href="<?=$back_url?>" class="btn btn-warning margin-right"><?=$lang['cancel']?></a><input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" /></div></div>
</div>
<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>