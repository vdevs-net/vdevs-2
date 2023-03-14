<?php
    $this->layout('layout');
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<div class="phdr"><?=$lang['infringements_history']?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <div><img src="<?=$site_path?>/assets/images/<?php if ($item['remain'] > 0): ?>red<?php else: ?>green<?php endif ?>.gif" width="16" height="16" align="left" /> <b><?=$item['type']?></b> <span class="gray">(<?=$item['time']?>)</span></div>
            <div><?=$item['reason']?></div>
            <div class="sub">
            <?php if ($item['ban_who']): ?>
                <div><span class="gray"><?=$lang['ban_who']?>:</span> <?=$item['ban_who']?></div>
            <?php endif ?>
                <div><span class="gray"><?=$lang['term']?>:</span> <?=$item['term']?></div>
            <?php if ($item['remain'] > 0): ?>
                <div><span class="gray"><?=$lang['remains']?>:</span> <?=$item['remain_time']?></div>
            <?php endif ?>
            <?php if ($item['menu']): ?>
                <div><?php foreach ($item['menu'] as $menu_item): ?><a href="<?=$menu_item['url']?>" class="btn"><?=$menu_item['name']?></a><?php endforeach?></div>
            <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
    <?php if ($delete_history_url || $pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <?php if ($delete_history_url): ?>
            <div class="menu"><a href="<?=$delete_history_url?>"><?=$lang['clear_history']?></a></div>
        <?php endif ?>
        <?php if ($pagination): ?>
            <div class="menu"><?=$pagination?></div>
        <?php endif ?>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
