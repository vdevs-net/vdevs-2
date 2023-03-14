<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>