<?php
    $this->layout('layout');
?>
<div class="rmenu"><small><?=$alert_text?></small></div>
<div class="menu">
<?php foreach ($bad_files as $idx => $data): ?>
    <div><?=$data['file_path']?></div>
<?php endforeach ?>
</div>
<div class="phdr"><?=$lang['total']?>: <?=count($bad_files)?></div>
<div class="menu"><a href="<?=$rescan_url?>"><?=$lang['antispy_rescan']?></a></div>