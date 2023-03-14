<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="menu">
    <ul>
<?php foreach ($engines as $engine => $details): ?>
        <li><img src="<?=$site_path?>/assets/images/<?=$engine?>.png" alt="<?=$engine?>" /> <a href="<?=$details['url']?>"><?=$details['name']?></a> (<?=$details['count']?>)</li>
<?php endforeach ?>
    </ul>
</div>
<div class="bmenu"><img src="<?=$site_path?>/assets/images/all1.png" alt="Tất cả" /> <a href="<?=$allUrl?>">Tất cả</a> (<?=$allCount?>)</div>
