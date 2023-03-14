<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<?php if ($items): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item"><?=$item['content']?></div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['voting_users_empty']?></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>
