<?php
    $this->layout('layout');
?>
<div class="menu"><a href="input"><?=$lang['received']?></a> (<?=$count_input?><?php if ($count_input_new): ?>/<span class="red">+<?=$count_input_new?></span><?php endif ?>)</div>
<div class="menu"><a href="output"><?=$lang['sent']?></a> (<?=$count_output?><?php if ($count_output_new): ?>/<span class="red">+<?=$count_output_new?></span><?php endif ?>)</div>
<div class="menu"><a href="systems"><?=$lang['systems']?></a> (<?=$count_systems?><?php if ($count_systems_new): ?>/<span class="red">+<?=$count_systems_new?></span><?php endif ?>)</div>
<div class="menu"><a href="files"><?=$lang['files']?></a> (<?=$count_file?>)</div>
<?php if ($can_write): ?>
    <div class="menu"><form action="write" method="post"><input type="submit" value="Soạn tin nhắn"/></form></div>
<?php endif ?>