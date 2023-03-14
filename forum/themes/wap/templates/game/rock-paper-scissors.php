<?php
    $this->layout('layout');
?>
<?php if ($game_result): ?>
    <?=$game_result?>
<?php elseif ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php else: ?>
    <div class="notif"><?=$game_description?></div>
<?php endif ?>

<?=$game_source?>