<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($total): ?>
    <div class="topmenu"><form action="ipban" method="get"><input type="hidden" name="mod" value="detail" /><input type="text" name="ip" autocomplete="off" placeholder="<?=$lang['ip_ban_search_help']?>" /><input type="submit" value="<?=$lang['search']?>" /></form></div>
    <?php foreach ($items as $item): ?>
        <div class="menu"><a href="<?=$item['detail_url']?>"><?=$item['ip']?></a>&nbsp;<?=$item['type']?></div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<div class="phdr"><?=$lang['total']?>: <?=$total?></div>
<?php if ($pagination): ?>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<div class="menu"><a href="ipban?mod=new"><?=$lang['ip_ban_new']?></a></div>
<?php if ($total): ?>
    <div class="menu"><a href="ipban?mod=clear"><?=$lang['ip_ban_clean']?></a></div>
<?php endif ?>