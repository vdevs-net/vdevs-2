<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="gmenu">
    <h3>Thống kê chung</h3>
    <ul>
        <li>Số lượt truy cập hôm nay: <?=$todayHit?></li>
        <li>Khách truy cập hôm nay: <?=$todayHost?></li>
        <li>Số lượt truy cập của  Robots: <?=$todayHitRobot?></li>
        <li>Số lượt truy cập không phải Robots: <?=$todayHitNoRobot?></li>
        <li>Số lượt truy cập trung bình: <?=$todayAverage?></li>
<?php if ($maxHost): ?>
        <li>Kỷ lục Hosts (<b><?=$maxHost?></b>) vào ngày <b><?=$maxHostTime?></b></li>
        <li>Kỷ lục Hits (<b><?=$maxHit?></b>) vào ngày <b><?=$maxHitTime?></b></li>
<?php endif ?>
    </ul>
</div>
<div class="menu">
    <h3>Các thống kê</h3>
    <ul>
        <li><a href="stats?mod=hosts">Host</a> (<?=$todayHost?>)</li>
        <li><a href="stats?mod=robots">Robots</a> (<?=$todayRobot?>)</li>
        <li><a href="stats?mod=users">Thành viên</a> (<?=$todayUser?>)</li>
        <li><a href="stats?mod=stat_search">Từ công cụ tìm kiếm</a> (<?=$todaySearch?> | <?=$todaySearchPercent?>%)</li>
        <li><a href="stats?mod=phones">Thiết bị - Trình duyệt</a></li>
        <li><a href="stats?mod=os">Hệ điều hành</a></li>
        <li><a href="stats?mod=referer">Chuyển hướng</a> (<?=$todayReferer?>)</li>
        <li><a href="stats?mod=point_in">Điểm vào</a></li>
        <li><a href="stats?mod=pop">Phổ biến</a> (<?=$todayPageViewed?>)</li>
        <li><a href="stats?mod=allstat">Tất cả thống kê</a></li>
    </ul>
</div>