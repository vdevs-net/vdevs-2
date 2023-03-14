<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-heading">Chỉnh sửa bài viết #<?=$position?></div>
<div class="panel-body">
<?php if ($preview_mode): ?>
    <div class="post reply-preview"><div class="postbody"><div class="content"><?=$preview_post?></div></div><!-- END .postbody --></div>
<?php endif ?>
<form name="form" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <?=$bbcode_editor?>
        <p><textarea rows="10" name="msg" class="form-control"><?=$input_message?></textarea></p>
    </div>
    <hr />
    <?php if ($add_image_mode): ?>
        <?php if ($recent_images): ?>
            <p><b>Chọn ảnh gần đây</b></p><p class="recent-images">
            <?php foreach($recent_images as $image): ?>
                <a href="javascript:tag('[img]<?=$image['img_link']?>', '[/img]\r\n')" tabindex="-1"><img src="<?=$image['img_thumb']?>" /></a>
            <?php endforeach ?>
            </p>
        <?php endif ?>
        <p><b>Upload ảnh mới</b></p><p><input type="file" name="image" accept=".png, .jpg, .jpeg" class="form-control" /></p>
        <hr />
    <?php endif ?>
    <div class="form-group">
        <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
        <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" class="btn btn-success" />
        <input type="submit" name="preview" value="<?=$lang['preview']?>" class="btn btn-info" />
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>"/>
</form>
<?php if ($add_image_mode): ?>
    <div class="alert alert-info">Sau khi chọn file, click "Đăng ảnh" để thêm ảnh vào bài viết</div>
<?php endif ?>
</div></div>