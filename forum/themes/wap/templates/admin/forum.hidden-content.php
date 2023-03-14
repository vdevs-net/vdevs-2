<?php
    $this->layout('layout');
?>
<?php if ($filter): ?>
    <div class="bmenu"><?=$filter?></div>
<?php endif ?>
<?php if ($total): ?>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
    <?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
        <form action="<?=$deleteFormAction?>" method="POST"><div class="rmenu"><input type="submit" name="submit" value="<?=$lang['delete_all']?>" /></div></form>
    <?php endif ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>