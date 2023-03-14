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
            <div><?=$item['message']?> <a href="<?=$item['post_url']?>">&gt;&gt;</a></div>
            <div class="sub"><span class="gray">(<?=$item['time']?>)</span></div>
        </div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>