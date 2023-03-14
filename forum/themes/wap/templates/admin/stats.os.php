<?php
    $this->layout('layout');
?>
<?php if ($items): ?>
<div class="menu"><img src="<?=$site_path?>/assets/images/stats/os.png" alt="Biểu đồ"/></div>
<div class="gmenu">
    <h3>Chi tiết</h3>
    <ul>
<?php foreach ($items as $item): ?>
        <li><?=$item['name']?> (<?=$item['count']?>)</li>
<?php endforeach ?>
    </ul>
</div>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>
