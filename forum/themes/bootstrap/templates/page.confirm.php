<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<form action="<?=$form_action?>" method="post" class="form-horizontal">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        <h4><?=$confirm_text?></h4>
        <?php if (isset($confirm_warning)): ?>
            <div class="alert alert-warning"><?=$confirm_warning?></div>
        <?php endif ?>
        <?php if (isset($confirm_options)): ?>
            <?php foreach ($confirm_options as $group): ?>
                <hr/>
                <div>
                    <p><?=$group['title']?></p>
                    <?php foreach ($group['items'] as $item): ?>
                        <div class="<?=$item['type']?>"><label><input type="<?=$item['type']?>" name="<?=$item['name']?>" value="<?=$item['value']?>" /> <?=$item['explain']?></label></div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        <hr />
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" name="submit" value="<?=$lang['yes']?>" class="btn btn-primary" />
                <a href="<?=$cancel_url?>" class="btn btn-danger"><?=$lang['no']?></a>
            </div>
        </div>
</form>
</div></div>