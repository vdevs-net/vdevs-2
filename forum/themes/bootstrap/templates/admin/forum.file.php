<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($fileTypes): ?>
    <?php foreach ($fileTypes as $type): ?>
        <div class="menu"><img src="<?=$type['iconUrl']?>" width="16" height="16" class="left" />&nbsp;<a href="<?=$type['url']?>"><?=$type['name']?></a> (<?=$type['count']?>)</div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>