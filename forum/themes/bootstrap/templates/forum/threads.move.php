<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<form action="<?=$form_action?>" method="post">
    <div class="form-group">
        <label for="forms" class="control-label"><?=$lang['section']?></label>
            <select name="razd" class="form-control"><?=$options?></select>
    </div>
    <div class="form-group">
        <input type="submit" name="submit" value="<?=$lang['move']?>" class="btn btn-primary" />
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div></div>