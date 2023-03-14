<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($super_admin): ?>
    <div class="bmenu"><?=$lang['supervisors']?></div>
    <?php foreach ($super_admin as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
<?php endif ?>
<?php if ($admin): ?>
    <div class="bmenu"><?=$lang['administrators']?></div>
    <?php foreach ($admin as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
<?php endif ?>
<?php if ($super_moder): ?>
    <div class="bmenu"><?=$lang['supermoders']?></div>
    <?php foreach ($super_moder as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
<?php endif ?>
<?php if ($moder): ?>
    <div class="bmenu"><?=$lang['moders']?></div>
    <?php foreach ($moder as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
<?php endif ?>
<div class="phdr"><?=$lang['total']?>: <?=$total?></div>
<div class="menu"><a href="usr"><?=$lang['users_list']?></a></div>