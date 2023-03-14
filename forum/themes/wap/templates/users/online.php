<?php
    $this->layout('layout');
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="<?=$item['html_class']?>"><?=$item['content']?></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>