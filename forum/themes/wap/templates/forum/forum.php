<?php
    $this->layout('layout');
?>
<div class="topmenu"><a href="<?=$search_url?>"><?=$lang['search']?></a><?php if ($forum_unread_count): ?> | <a href="<?=$forum_unread_url?>"><?=$lang['unread']?></a>&#160;<span class="red">(<b><?=$forum_unread_count?></b>)</span><?php endif ?></div>
<?php if ($categories): ?>
    <?php foreach ($categories as $cat): ?>
    <div class="box">
        <div class="phdr" id="<?=$cat['html_id']?>">
            <h3 class="cat-name"><a href="#<?=$cat['html_id']?>"><?=$cat['name']?></a></h3>
            <?php if ($cat['description']): ?><div class="sub"><?=$cat['description']?></div><?php endif ?>
        </div>
        <?php if ($cat['forums']): ?>
            <?php foreach ($cat['forums'] as $forum): ?>
            <div class="menu">
                <div><a href="<?=$forum['url']?>"><?=$forum['name']?></a></div>
                <?php if ($forum['description']): ?><div class="sub"><?=$forum['description']?></div><?php endif ?>
            </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="rmenu"><?=$lang['section_list_empty']?></div>
        <?php endif ?>
    </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="rmenu">Diễn đàn chưa có chuyên mục nào! Nếu bạn là quản trị viên, vui lòng tạo chuyên mục diễn đàn trước.</div>
<?php endif ?>
<div class="phdr"><?php if ($loged): ?><a href="<?=$forum_online_url?>"><?=$lang['who_in_forum']?></a><?php else: ?><?=$lang['who_in_forum']?><?php endif ?> (<?=$online_users?> / <?=$online_guests?>)</div>