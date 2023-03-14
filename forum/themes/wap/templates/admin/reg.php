<?php
    $this->layout('layout');
?>
<?php if ($pagination): ?>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item?></div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<?php if ($pagination || $total): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <?php if ($total): ?>
        <div class="menu"><a href="reg?mod=massapprove"><?=$lang['reg_approve_all']?></a></div>
        <div class="menu"><a href="reg?mod=massdel"><?=$lang['reg_del_all']?></a></div>
    <?php endif ?>
<?php endif ?>