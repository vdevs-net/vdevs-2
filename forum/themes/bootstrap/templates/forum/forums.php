<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="clearfix margin-bottom">
    <div class="pull-right"><a href="<?=$search_url?>" class="btn btn-primary btn-sm"><?=$lang['search']?></a><?php if ($forum_unread_count): ?><a href="<?=$forum_unread_url?>" class="btn btn-danger btn-sm margin-left"><?=$lang['unread']?> <span class="badge"><b><?=$forum_unread_count?></b></span></a><?php endif ?><?php if ($can_create_thread): ?><a href="<?=$create_thread_url?>" method="post" class="btn btn-success btn-sm margin-left" ><?=$lang['new_topic']?></a>
<?php endif ?></div>
</div>
<div class="panel panel-primary">
<?php if ($threads): ?>
    <div class="list-group">
    <?php foreach ($threads as $thread): ?>
        <div class="list-group-item <?=$thread['html_class']?>">
            <div><?php if ($thread['icons']): ?><?php foreach ($thread['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($thread['prefix']): ?><span class="label label-<?=$thread['prefix']?>"><?=$thread['prefix_name']?></span><?php endif ?><a href="<?=$thread['url']?>"><?=$thread['name']?></a> [<?=$thread['post_count']?>]<?php if ($thread['last_page_url']): ?> <a href="<?=$thread['last_page_url']?>">&gt;&gt;</a><?php endif ?></div>
            <div class="sub"><?=$thread['author_name']?><?php if ($thread['post_count'] > 1): ?> / <?=$thread['last_user_name']?> <?php endif ?> <span class="gray">(<?=$thread['last_time']?>)</span></div>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['topic_list_empty']?></div>
<?php endif ?>
</div>

<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>
