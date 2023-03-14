<?php
    $this->layout('layout');
?>
<?php if ($pagination): ?>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<?php if ($threads): ?>
    <?php foreach ($threads as $thread): ?>
        <div class="<?=$thread['html_class']?>">
            <div><?php if ($thread['icons']): ?><?php foreach ($thread['icons'] as $icon): ?><img src="<?=$site_path?>/assets/images/<?=$icon?>.gif" class="icon" alt="[*]" /><?php endforeach ?><?php endif ?><?php if ($thread['prefix']): ?><span class="label label-<?=$thread['prefix']?>"><?=$thread['prefix_name']?></span><?php endif ?><a href="<?=$thread['url']?>"><?=$thread['name']?></a> [<?=$thread['post_count']?>]<?php if ($thread['last_page_url']): ?> <a href="<?=$thread['last_page_url']?>">&raquo;</a><?php endif ?></div>
            <div class="sub">
                <div><?=$thread['author_name']?><?php if ($thread['post_count'] > 1): ?> / <?=$thread['last_user_name']?><?php endif ?> <span class="gray">(<?=$thread['last_time']?>)</span></div>
                <div><?=$lang['section']?>: <a href="<?=$thread['parent_url']?>"><?=$thread['parent_name']?></a></div>
            </div>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <div class="topmenu"><?=$pagination?></div>
    <div clas="menu">
        <form action="<?=$pagination_form_action?>" method="get"><?=$hidden_input?><input type="text" name="page" size="2" value="<?=$current_page?>" /><input type="submit" value="<?=$lang['to_page']?> &gt;&gt;"/></form></div>
<?php endif ?>
<?php if ($show_unread_mark_link): ?>
    <div class="menu"><a href="<?=$unread_mark_url?>"><?=$lang['unread_reset']?></a></div>
<?php endif ?>
