<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<div class="panel panel-primary with-nav-tabs">
<div class="panel-heading"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <div><a href="<?=$item['thread_url']?>"><strong><?=$item['thread_name']?></strong></a></div>
            <div><?=$item['message']?> <a href="<?=$item['post_url']?>">&gt;&gt;</a></div>
            <div class="sub"><span class="gray">(<?=$item['time']?>)</span></div>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>