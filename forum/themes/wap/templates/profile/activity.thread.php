<?php
    $this->layout('layout');
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<div class="phdr"><?=$lang['activity']?></div>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <div><a href="<?=$item['thread_url']?>"><strong><?=$item['thread_name']?></strong></a></div>
            <div><?=$item['message']?></div>
            <div class="sub">
                <div><a href="<?=$item['parent_url']?>"><?=$item['parent_name']?></a></div>
                <div><span class="gray">(<?=$item['time']?>)</span></div>
            </div>
        </div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>