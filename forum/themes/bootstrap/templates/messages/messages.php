<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($total): ?>
<div class="clearfix margin-bottom">
    <div class="pull-right">
        <a href="<?=$clear_url?>" class="btn btn-danger btn-sm"><?=$lang['clear_messages']?></a>
    </div>
</div>
<?php endif ?>
<div class="panel-group">
<?php if ($can_write): ?>
<div class="panel panel-primary">
<div class="panel-body">
    <form name="form" action="<?=$form_action?>" method="post"  enctype="multipart/form-data">
        <div class="form-group">
            <?=$bbcode_editor ?>
            <p><textarea rows="<?=$user['field_h']?>" name="text" class="form-control" placeholder="<?=$lang['message']?>"></textarea></p>
        </div>
        <div class="form-group">
            <input type="file" name="fail" class="form-control" />
        </div>
        <div class="form-group"><input type="submit" name="submit" value="<?=$lang['sent']?>" class="btn btn-primary btn-block" /></div>
    </form>
</div>
</div>
<?php endif ?>
<div class="panel panel-primary">
<?php if ($total): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item <?=$item['html_class']?>"><?=$item['content']?></div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
<div class="clearfix margin-top">
    <div class="pull-right paging"><?=$pagination?></div>
</div>
<?php endif ?>
</div>