<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary with-nav-tabs">
<div class="panel-heading"><?=$this->display_tab($tabs)?></div>
<?php if ($items): ?>
    <div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item"><?=$item['content']?></div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>
</div>