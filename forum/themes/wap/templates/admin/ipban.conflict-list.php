<?php
    $this->layout('layout');
?>
<div class="rmenu"><?=$lang['ip_ban_conflict_address']?></div>
<?php foreach ($items as $item): ?>
    <div class="menu"><a href="<?=$item['detail_url']?>"><?=$item['ip']?></a>&nbsp;<?=$item['type']?></div>
<?php endforeach ?>
<div class="phdr"><?=$lang['total']?>: <?=$total?></div>
<div class="menu"><a href="ipban?mod=new"><?=$lang['back']?></a></div>