<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
    <?php if ($total): ?>
        <div class="list-group">
        <?php foreach ($items as $item): ?>
            <div class="list-group-item"><?php if ($item['search_url']): ?><a href="<?=$item['search_url']?>"><?=$item['ip']?></a><?php else: ?><?=$item['ip']?><?php endif ?> <span class="gray">(<?=$item['time']?>)</span></div>
        <?php endforeach ?>
        </div>
        <?php if ($pagination): ?>
            <div class="panel-footer"><?=$pagination?></div>
        <?php endif ?>
    <?php else: ?>
        <div class="panel-body"><?=$lang['list_empty']?></div>
    <?php endif ?>
</div>