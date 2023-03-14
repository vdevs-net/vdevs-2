<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="clearfix margin-bottom">
    <div class="pull-right"><a href="<?=$search_url?>" class="btn btn-sm btn-primary"><?=$lang['search']?></a><?php if ($forum_unread_count): ?><a href="<?=$forum_unread_url?>" class="btn btn-danger btn-sm margin-left"><?=$lang['unread']?> <span class="badge"><b><?=$forum_unread_count?></b></span></a><?php endif ?></div>
</div>
<div class="panel panel-primary">
<?php if ($forums): ?>
    <div class="list-group">
    <?php foreach ($forums as $forum): ?>
        <div class="list-group-item">
            <h4 class="list-group-item-heading"><a href="<?=$forum['url']?>"><?=$forum['name']?></a> [<?=$forum['thread_count']?>]</h4>
            <?php if ($forum['description']): ?><div class="list-group-item-text"><?=$forum['description']?></div><?php endif ?>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['section_list_empty']?></p></div>
<?php endif ?>
</div>