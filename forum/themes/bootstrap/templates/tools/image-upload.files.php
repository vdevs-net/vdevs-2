<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-default">
<div class="panel-heading"> Uploaded by <a href="<?=$user_profile_url?>" class="<?=$user_html_class?>"><?=$user_name?></a></div>
<?php if ($total): ?>
    <div class="list-group images-list">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <a href="<?=$item['details_url']?>" class="image-thumb"><img src="<?=$item['thumb_src']?>" width="90" height="90" /></a>
            <div class="image-details">
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