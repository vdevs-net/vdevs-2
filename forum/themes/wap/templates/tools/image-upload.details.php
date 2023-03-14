<?php
    $this->layout('layout');
?>
<div class="menu">
    <center>
        <div><img src="<?=$thumb_src?>" width="<?=$thumb_width?>" height="<?=$thumb_height?>" /></div>
        <div><a href="<?=$image_src?>" target="_blank">View full size</a></div>
    </center>
</div>
<div class="menu">
<div><b>Uploader</b>: <a href="<?=$uploader_profile_url?>" class="<?=$uploader_html_class?>"><?=$uploader?></a></div>
<div><b>Upload time</b>: <?=$upload_time?></div>
<div><b>File size</b>: <?=$file_size?> KB</div>
<div><b>Image size</b>: <?=$file_width?>x<?=$file_height?></div>
<div><a href="files?id=<?=$uploader_id?>">More images from <?=$uploader?></a></div></div>
<div class="menu">Link: <input type="text" value="<?=$image_src?>" /></div>
<div class="menu">BBcode: <input type="text" value="[img]<?=$image_src?>[/img]" /></div>
<div class="menu">BBcode: <input type="text" value="[img=<?=$file_width?>x<?=$file_height?>]<?=$image_src?>[/img]" /></div>
<?php if ($can_delete): ?>
    <div class="rmenu"><a href="<?=$delete_url?>">Delete</a></div>
<?php endif ?>