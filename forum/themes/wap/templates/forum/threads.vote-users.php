<?php
    $this->layout('layout');
?>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><?=$lang['voting_users_empty']?></div>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>