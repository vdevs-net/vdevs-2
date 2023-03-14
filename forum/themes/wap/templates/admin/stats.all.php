<?php
    $this->layout('layout');
?>
<?php if ($hasData): ?>
<div class="gmenu">
    <h3>Thống kê cho <?=$currentDay?></h3>
    <ul>
        <li>Hosts: <?=$currentDayHost?></li>
        <li>Hits: <?=$currentDayHits?></li>
    </ul>
    <h3>Truy cập từ công cụ tìm kiếm: <?=$currentDaySearchCount?></h3>
    <?php if ($currentDaySearchCount): ?>
        <ul>
        <?php foreach ($currentDaySearch as $key => $value): ?>
            <?php if ($value > 0): ?>
                <li><img src="<?=$site_path?>/assets/images/<?=$key?>.png" alt="<?=$key?>" /> <?=ucfirst($key)?> (<?=$value?>)</li>
            <?php endif ?>
        <?php endforeach ?>
        </ul>
    <?php endif ?>
</div>
<div class="menu">
    <h3>Thống kê chung</h3>
    <ul>
        <li>Khách truy cập: <b><?=$allHost?></b></li>
        <li>Lượt truy cập: <b><?=$allHits?></b></li>
    </ul>
    <h3>Từ công cụ tìm kiếm: <?=$allSearchCount?></h3>
    <ul>
<?php foreach ($allSearch as $key => $value): ?>
        <li><img src="<?=$site_path?>/assets/images/<?=$key?>.png" alt="<?=$key?>" /> Từ <?=ucfirst($key)?>: <b><?=$value?></b></li>
<?php endforeach ?>
    </ul>
</div>
<div class="bmenu">
    <h3>Biểu đồ</h3>
    <div><img src="<?=$site_path?>/assets/images/stats/we.png" alt="7 ngày gần đây" /></div>
    <h4>Lưu lượng tìm kiếm</h4>
    <div><img src="<?=$site_path?>/assets/images/stats/se.png" alt="So sánh các công cụ tìm kiếm" /></div>
</div>
<?php else: ?>
    <div class="rmenu">Không có thông tin cho ngày <?=$currentDay?></div>
<?php endif ?>
<div class="menu"><a href="<?=$prevDayUrl?>">Xem ngày <?=$prevDay?></a></div>
