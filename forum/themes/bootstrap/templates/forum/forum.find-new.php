<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($show_unread_mark_link): ?>
        <div class="clearfix margin-bottom"><div class="pull-right"><a href="<?=$unread_mark_url?>" class="btn btn-danger btn-sm"><?=$lang['unread_reset']?></a></div></div>
<?php endif ?>
<div class="panel panel-primary">
<?php if ($threads): ?>
    <div class="list-group">
    <?php foreach ($threads as $thread): ?>
        <div class="list-group-item <?=$thread['html_class']?>">
            <div><?php if ($thread['icons']): ?><?php foreach ($thread['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($thread['prefix']): ?><span class="label label-<?=$thread['prefix']?>"><?=$thread['prefix_name']?></span><?php endif ?><a href="<?=$thread['url']?>"><?=$thread['name']?></a> [<?=$thread['post_count']?>]<?php if ($thread['last_page_url']): ?> <a href="<?=$thread['last_page_url']?>">&raquo;</a><?php endif ?></div>
            <div class="sub">
                <div><?=$thread['author_name']?><?php if ($thread['post_count'] > 1): ?> / <?=$thread['last_user_name']?><?php endif ?> <span class="gray">(<?=$thread['last_time']?>)</span></div>
                <div><?=$lang['section']?>: <a href="<?=$thread['parent_url']?>"><?=$thread['parent_name']?></a></div>
            </div>
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
