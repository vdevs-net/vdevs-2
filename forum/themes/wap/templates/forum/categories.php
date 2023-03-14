<?php
    $this->layout('layout');
?>
<div class="topmenu"><a href="<?=$search_url?>"><?=$lang['search']?></a><?php if ($forum_unread_count): ?> | <a href="<?=$forum_unread_url?>"><?=$lang['unread']?></a>&#160;<span class="red">(<b><?=$forum_unread_count?></b>)</span><?php endif ?></div>
<?php if ($forums): ?>
    <?php foreach ($forums as $forum): ?>
    <div class="menu">
        <div><a href="<?=$forum['url']?>"><?=$forum['name']?></a> [<?=$forum['thread_count']?>]</div>
        <?php if ($forum['description']): ?><div class="sub"><span class="gray"><?=$forum['description']?></span></div><?php endif ?>
    </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p>' . $lng_forum['section_list_empty'] . '</p></div>
<?php endif ?>