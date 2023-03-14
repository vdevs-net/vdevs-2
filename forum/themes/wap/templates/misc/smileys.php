<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="list1"><img src="<?=$item['src']?>" alt="<?=$item['symbol']?>" /> <?=$item['symbol']?></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>