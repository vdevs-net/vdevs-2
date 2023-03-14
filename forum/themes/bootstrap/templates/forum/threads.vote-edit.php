<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="poll_question" class="control-label col-sm-3"><?=$lang['voting']?></label>
            <div class="col-sm-9">
                <input type="text" size="20" maxlength="150" name="poll_question" value="<?=$poll_question?>" id="poll_question" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3"><?=$lang['answers']?></label>
            <div class="col-sm-9">
            <ul>
            <?php foreach ($poll_responses as $response): ?>
                <li class="margin-bottom-sm"><?php if ($response['delete_url']): ?><div class="input-group"><input type="text" name="existing_response[<?=$response['id']?>]" value="<?=$response['text']?>" class="form-control" /><span class="input-group-addon"><a href="<?=$response['delete_url']?>">[x]</a></span></div><?php else: ?><input type="text" name="existing_response[<?=$response['id']?>]" value="<?=$response['text']?>" class="form-control" /><?php endif ?></li>
            <?php endforeach ?>
            <?php if ($input_new_responses): ?>
                <?php foreach ($input_new_responses as $new_response): ?>
                    <li class="margin-bottom-sm"><input type="text" name="new_response[]" value="<?=$new_response?>" class="form-control" placeholder="<?=$lang['answer']?>" /></li>
                <?php endforeach ?>
            <?php endif ?>
            </ul>
            </div>
        </div>
        <hr />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" /></div>
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>
</div>