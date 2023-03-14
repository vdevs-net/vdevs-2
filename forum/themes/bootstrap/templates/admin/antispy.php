<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="menu"><p><h3><?=$lang['antispy_scan_mode']?></h3><ul>
    <li><a href="antispy?mod=scan"><?=$lang['antispy_dist_scan']?></a><br /><small><?=$lang['antispy_dist_scan_help']?></small></li>
    <li><a href="antispy?mod=snapscan"><?=$lang['antispy_snapshot_scan']?></a><br /><small><?=$lang['antispy_snapshot_scan_help']?></small></li>
    <li><a href="antispy?mod=snap"><?=$lang['antispy_snapshot_create']?></a><br /><small><?=$lang['antispy_snapshot_create_help']?></small></li>
</ul></p></div>