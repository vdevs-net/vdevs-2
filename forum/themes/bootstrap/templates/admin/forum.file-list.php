<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <h4><?=$item['icon']?><a href="<?=$item['url']?>" class="noPusher" target="_blank"><?=$item['name']?></a></h4>
            <?php if ($item['thumb']): ?>
                <div><a href="<?=$item['url']?>" class="noPusher" target="_blank"><img src="<?=$item['thumb']?>"  alt="<?=$lang['click_to_view']?>" /></a></div>
            <?php endif ?>
            <div><small><span class="gray"><?=$lang['size']?>: <?=$item['size']?> kb.<br /><?=$lang['downloaded']?>: <?=$item['downloaded']?> <?=$lang['time']?></span></small></div>
            <div class="sub">
                <div>[Thread] <a href="<?=$item['threadUrl']?>"><b><?=$item['threadName']?></b></a></div>
                <div><b>Author</b> : <?=$item['uploader']?></div>
            </div>
        </div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>