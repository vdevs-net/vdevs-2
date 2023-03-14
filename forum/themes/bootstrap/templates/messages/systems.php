<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($total): ?>
 <div class="clearfix margin-bottom">
    <div class="pull-right"><a href="systems?mod=clear" class="btn btn-danger btn-sm"><?=$lang['clear_messages']?></a></div>
</div>
<div class="panel panel-primary">
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <div><strong><?=$item['title']?></strong> (<?=$item['time']?>)</div>
            <div><?=$item['message']?></div>
            <div class="sub"><a href="<?=$item['delete_url']?>"><?=$lang['delete']?></a></div>
        </div>
    <?php endforeach ?>
    </div>
    </div>
<?php else: ?>
    <div class="panel panel-primary"><div class="panel-body"><p><?=$lang['list_empty']?></p></div></div>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>