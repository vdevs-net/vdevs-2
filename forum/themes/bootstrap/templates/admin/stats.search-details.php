<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
<?php if ($pagination): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<?php else: ?>
    <div class="rmenu">Danh sách trống!</div>
<?php endif ?>
