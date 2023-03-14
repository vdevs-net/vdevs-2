<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$lang['ip_history']?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><a href="<?=$item['url']?>"><?=$item['ip']?></a> <span class="gray">(<?=$item['time']?>)</span></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>