<?php
    $this->layout('layout');
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="<?=$item['html_class']?>"><?=$item['content']?></div>
    <?php endforeach ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
        <div class="menu"><a href="ban-panel?mod=amnesty"><?=$lang['amnesty']?></a></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>