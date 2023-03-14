<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="menu"><img src="<?=$site_path?>/assets/images/stats/model.png" alt="Biểu đồ"/></div>
<div class="gmenu">
    <h3>Chi tiết</h3>
    <ul>
<?php foreach ($phones as $phone): ?>
        <li><a href="<?=$phone['url']?>"><?=$phone['name']?></a> (<?=$phone['count']?>)</li>
<?php endforeach ?>
    </ul>
</div>
