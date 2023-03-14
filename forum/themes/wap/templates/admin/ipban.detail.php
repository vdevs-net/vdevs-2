<?php
    $this->layout('layout');
?>
<div class="rmenu"><p><?=$ban_ip?></p></div>
<div class="menu"><p><h3><?=$lang['ban_type']?></h3>&nbsp;<?=$ban_type?></p>
<p><h3><?=$lang['reason']?></h3>&nbsp;<?=$ban_reason?></p></div>
<div class="menu">
    <p><?=$lang['ban_who']?>: <b><?=$ban_who?></b></p>
    <p><?=$lang['date']?>: <b><?=$ban_date?></b></p>
    <p><?=$lang['time']?>: <b><?=$ban_time?></b></p>
</div>
<div class="menu"><a href="<?=$ban_del_url?>" class="btn"><?=$lang['ip_ban_del']?></a></div>