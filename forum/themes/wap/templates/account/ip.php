<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?php if ($item['search_url']): ?><a href="<?=$item['search_url']?>"><?=$item['ip']?></a><?php else: ?><?=$item['ip']?><?php endif ?> <span class="gray">(<?=$item['time']?>)</span></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>