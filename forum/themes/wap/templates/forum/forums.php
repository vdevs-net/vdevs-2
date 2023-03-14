<?php
    $this->layout('layout');
?>
<div class="topmenu"><a href="<?=$search_url?>"><?=$lang['search']?></a><?php if ($forum_unread_count): ?> | <a href="<?=$forum_unread_url?>"><?=$lang['unread']?></a>&#160;<span class="red">(<b><?=$forum_unread_count?></b>)</span><?php endif ?></div>
<?php if ($can_create_thread): ?>
    <div class="gmenu"><form action="<?=$create_thread_url?>" method="post"><input type="submit" value="<?=$lang['new_topic']?>" /></form></div>
<?php endif ?>
<?php if ($threads): ?>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <?php foreach ($threads as $thread): ?>
        <div class="<?=$thread['html_class']?>">
            <div><?php if ($thread['icons']): ?><?php foreach ($thread['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($thread['prefix']): ?><span class="label label-<?=$thread['prefix']?>"><?=$thread['prefix_name']?></span><?php endif ?><a href="<?=$thread['url']?>"><?=$thread['name']?></a> [<?=$thread['post_count']?>]<?php if ($thread['last_page_url']): ?> <a href="<?=$thread['last_page_url']?>">&gt;&gt;</a><?php endif ?></div>
            <div class="sub"><?=$thread['author_name']?><?php if ($thread['post_count'] > 1): ?> / <?=$thread['last_user_name']?> <?php endif ?> <span class="gray">(<?=$thread['last_time']?>)</span></div>
        </div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['topic_list_empty']?></p></div>
<?php endif ?>
