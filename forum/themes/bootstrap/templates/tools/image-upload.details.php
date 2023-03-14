<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <center>
        <div><img src="<?=$thumb_src?>" width="<?=$thumb_width?>" height="<?=$thumb_height?>" /></div>
        <div><a class="btn btn-sm btn-primary margin-top" href="<?=$image_src?>" target="_blank">View full size</a></div>
    </center>
</div>
<div class="list-group">
<a href="<?=$uploader_profile_url?>" class="list-group-item"><b>Uploader</b>: <span class="<?=$uploader_html_class?>"><?=$uploader?></span></a>
<div class="list-group-item"><b>Upload time</b>: <?=$upload_time?></div>
<div class="list-group-item"><b>File size</b>: <?=$file_size?> KB</div>
<div class="list-group-item"><b>Image size</b>: <?=$file_width?>x<?=$file_height?></div>
<a href="files?id=<?=$uploader_id?>" class="list-group-item">More images from <span class="<?=$uploader_html_class?>"><?=$uploader?></span></a>
<div class="list-group-item fixedWidthAddon">
    <div class="input-group margin-bottom">
        <span class="input-group-addon">Link</span>
        <input type="text" class="form-control" value="<?=$image_src?>">
    </div>
    <div class="input-group margin-bottom">
        <span class="input-group-addon">BBcode</span>
        <input type="text" class="form-control" value="[img]<?=$image_src?>[/img]">
    </div>
    <div class="input-group">
        <span class="input-group-addon">BBcode 2</span>
        <input type="text" class="form-control" value="[img=<?=$file_width?>x<?=$file_height?>]<?=$image_src?>[/img]">
    </div>
</div>
</div>
<?php if ($can_delete): ?>
    <div class="panel-footer"><a href="<?=$delete_url?>" class="btn btn-danger btn-sm">Delete</a></div>
<?php endif ?>
</div>