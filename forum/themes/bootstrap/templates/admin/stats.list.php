<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="rmenu"><?=$lang['list_empty']?></div>
<?php endif ?>