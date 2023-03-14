<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<?php if ($game_result): ?>
    <?=$game_result?>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php else: ?>
    <div class="alert alert-info"><?=$game_description?></div>
<?php endif ?>

<?=$game_source?>
</div></div>