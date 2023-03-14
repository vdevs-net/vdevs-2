<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="list-group">
<div class="list-group-item"><a href="input"><?=$lang['received']?></a> (<?=$count_input?><?php if ($count_input_new): ?>/<span class="red">+<?=$count_input_new?></span><?php endif ?>)</div>
<div class="list-group-item"><a href="output"><?=$lang['sent']?></a> (<?=$count_output?><?php if ($count_output_new): ?>/<span class="red">+<?=$count_output_new?></span><?php endif ?>)</div>
<div class="list-group-item"><a href="systems"><?=$lang['systems']?></a> (<?=$count_systems?><?php if ($count_systems_new): ?>/<span class="red">+<?=$count_systems_new?></span><?php endif ?>)</div>
<div class="list-group-item"><a href="files"><?=$lang['files']?></a> (<?=$count_file?>)</div>
</div>
<?php if ($can_write): ?>
    <div class="panel-footer"><form action="write" method="post"><input type="submit" value="Soạn tin nhắn" class="btn btn-primary" /></form></div>
<?php endif ?>
</div>