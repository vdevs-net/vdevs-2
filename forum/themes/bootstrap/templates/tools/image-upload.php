<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($loged): ?>
    <div class="clearfix margin-bottom">
    <div class="pull-right">
        <a href="upload" class="btn btn-primary btn-sm">Upload new image</a>
        <a href="files" class="btn btn-default btn-sm margin-left">My images [<b class="red"><?=$my_images?></b>]</a>
    </div>
    </div>
<?php endif ?>
<div class="panel panel-primary">
<?php if ($total): ?>
    <div class="list-group images-list">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <a class="image-thumb" href="<?=$item['details_url']?>"><img src="<?=$item['thumb_src']?>" width="90" height="90" /></a>
            <div class="image-details">
                <div><b>Uploader</b>: <a href="files?id=<?=$item['uploader_id']?>" class="<?=$item['uploader_html_class']?>"><?=$item['uploader']?></a></div>
                <div><b>Upload time</b>: <?=$item['upload_time']?></div>
                <div><b>File size</b>: <?=$item['file_size']?> KB</div>
            </div>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>