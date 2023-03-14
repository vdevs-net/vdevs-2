<?php
    $this->layout('layout');
?>
<?php if ($rights > RIGHTS_SUPER_MODER): ?>
    <div class="topmenu"><a href="<?=$add_news_url?>"><?=$lang['add']?></a> | <a href="<?=$clean_news_url?>"><?=$lang['clear']?></a></div>
<?php endif ?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <h3><?=$item['title']?></h3>
            <div class="gray"><small><?=$lang['author']?>: <?=$item['author']?> (<?=$item['time']?>)</small></div>
            <div class="text"><?=$item['content']?></div>
            <div class="sub">
            <?php if ($item['comment_url']): ?>
                <div><a href="<?=$item['comment_url']?>"><?=$lang['discuss_on_forum']?> (<?=$item['comment_count']?>)</a></div>
            <?php endif ?>
            <?php if ($rights >= RIGHTS_SUPER_MODER): ?>
                <div><a href="<?=$item['edit_url']?>"><?=$lang['edit']?></a> | <a href="<?=$item['delete_url']?>"><?=$lang['delete']?></a></div>
            <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>