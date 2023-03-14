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
        <label for="name" class="control-label col-sm-3"><?=$lang['article_title']?></label>
        <div class="col-sm-9">
            <input type="text" name="name" autocomplete="off" value="<?=$input_title?>" class="form-control" id="name" />
        </div>
    </div>
    <div class="form-group">
        <label for="content" class="control-label col-sm-3"><?=$lang['text']?></label>
        <div class="col-sm-9">
            <textarea rows="10" name="text" class="form-control" id="content"><?=$input_content?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3"><?=$lang['discuss']?></label>
        <div class="col-sm-9">
            <select name="forum_id" class="form-control">
                <option value="0"><?=$lang['discuss_off']?></option>
                <?php foreach ($categories as $cat): ?>
                    <optgroup label="<?=$cat['name']?>">
                    <?php foreach ($cat['items'] as $item): ?>
                        <option <?php if ($item['id'] == $input_forum_id): ?>selected="selected" <?php endif ?>value="<?=$item['id']?>"><?=$item['name']?></option>
                    <?php endforeach ?>
                    </optgroup>
                <?php endforeach ?>
            </select>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
        </div>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
</div></div>