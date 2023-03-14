<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="list-group">
<?php foreach ($items as $item): ?>
    <div class="list-group-item"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
<?php endforeach ?>
</div>
</div>