<?php
    $this->layout('layout');
?>
<?php if ($loged): ?>
    <div class="topmenu"><a href="upload">Upload new image</a> | <a href="files">My images</a> [<b class="red"><?=$my_images?></b>]</div>
<?php endif ?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <table class="menu" width="100%"><tr valign="top"><td width="94px"><a href="<?=$item['details_url']?>"><img src="<?=$item['thumb_src']?>" width="90" height="90" /></a></td><td><div><b>Uploader</b>: <a href="files?id=<?=$item['uploader_id']?>" class="<?=$item['uploader_html_class']?>"><?=$item['uploader']?></a></div><div><b>Upload time</b>: <?=$item['upload_time']?></div><div><b>File size</b>: <?=$item['file_size']?> Kb</div></td></tr></table>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="rmenu"><?=$lang['list_empty']?></div>
<?php endif ?>