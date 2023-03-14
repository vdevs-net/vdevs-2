<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <div><strong><?=$item['title']?></strong> (<?=$item['time']?>)</div>
            <div><?=$item['message']?></div>
            <div class="sub"><a href="<?=$item['delete_url']?>"><?=$lang['delete']?></a></div>
        </div>
    <?php endforeach ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <div class="menu"><a href="systems?mod=clear"><?=$lang['clear_messages']?></a></div>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>