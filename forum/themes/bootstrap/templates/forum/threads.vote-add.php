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
        <label for="poll_question" class="control-label col-sm-3"><?=$lang['voting']?></label>
        <div class="col-sm-9">
            <input type="text" size="20" maxlength="150" name="poll_question" value="<?=$input_question?>" class="form-control" id="poll_question" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3"><?=$lang['answers']?></label>
        <div class="col-sm-9">
        <ul>
        <?php for ($i = 0; $i < MAX_POLL_RESPONSE; $i++): ?>
            <li class="margin-bottom-sm"><input type="text" name="poll_response[]" value="<?=$input_responses[$i]?>" placeholder="<?=$lang['answer']?>" autocomplete="off" class="form-control" /></li>
        <?php endfor ?>
        </ul>
        </div>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div>
</div>