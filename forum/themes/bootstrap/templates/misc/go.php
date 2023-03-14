<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<form action="<?=$form_action?>" method="post" class="form-horizontal noPusher">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        <h4><?=$confirm_text?></h4>
        <hr />
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" name="submit" value="<?=$lang['yes']?>" class="btn btn-primary" />
                <a href="<?=$cancel_url?>" class="btn btn-danger"><?=$lang['no']?></a>
            </div>
        </div>
</form>
</div></div>