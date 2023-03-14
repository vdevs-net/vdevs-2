<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<div class="panel panel-primary">
<div class="panel-heading"><?=$lang['infringements_history']?></div>
<?php if ($total): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
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
                <div class="margin-top-sm"><?php foreach ($item['menu'] as $menu_item): ?><a href="<?=$menu_item['url']?>" class="btn btn-danger btn-xs margin-right"><?=$menu_item['name']?></a><?php endforeach?></div>
            <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
    </div>
    <?php if ($delete_history_url): ?>
        <div class="panel-footer text-right"><a href="<?=$delete_history_url?>" class="btn btn-danger btn-sm"><?=$lang['clear_history']?></a></div>
    <?php endif ?>
<?php else: ?>
    <div class="panel-body"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>
