<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($rights > RIGHTS_SUPER_MODER): ?>
<div class="clearfix margin-bottom">
    <div class="pull-right">
        <a href="<?=$add_news_url?>" class="btn btn-sm btn-primary"><?=$lang['add']?></a>
        <a href="<?=$clean_news_url?>" class="btn btn-sm btn-danger margin-left"><?=$lang['clear']?></a>
    </div>
</div>
<?php endif ?>
<div class="panel panel-primary">
<?php if ($total): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <h4 class="list-group-item-heading"><?=$item['title']?></h4>
            <div class="gray"><small><?=$lang['author']?>: <?=$item['author']?> (<?=$item['time']?>)</small></div>
            <div class="text"><?=$item['content']?></div>
            <?php if ($item['comment_url'] || $rights >= RIGHTS_SUPER_MODER): ?>
            <div class="sub clearfix">
            <div class="pull-right">
            <?php if ($item['comment_url']): ?>
                <a href="<?=$item['comment_url']?>" class="btn btn-primary btn-xs margin-left"><?=$lang['discuss_on_forum']?> <span class="badge"><?=$item['comment_count']?></span></a>
            <?php endif ?>
            <?php if ($rights >= RIGHTS_SUPER_MODER): ?>
                <a href="<?=$item['edit_url']?>" class="btn btn-success btn-xs margin-left"><?=$lang['edit']?></a><a href="<?=$item['delete_url']?>" class="btn btn-danger btn-xs margin-left"><?=$lang['delete']?></a>
            <?php endif ?>
            </div>
            </div>
            <?php endif ?>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>