<?php
    $this->layout('layout');
?>
<div class="topmenu"> Uploaded by <a href="<?=$user_profile_url?>" class="<?=$user_html_class?>"><?=$user_name?></a></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <table class="menu" width="100%"><tr valign="top"><td width="94px"><a href="<?=$item['details_url']?>"><img src="<?=$item['thumb_src']?>" width="90" height="90" /></a></td><td><div><b>Upload time</b>: <?=$item['upload_time']?></div><div><b>File size</b>: <?=$item['file_size']?> KB</div></td></tr></table>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="rmenu"><?=$lang['list_empty']?></div>
<?php endif ?>