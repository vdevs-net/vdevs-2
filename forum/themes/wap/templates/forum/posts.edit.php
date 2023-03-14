<?php
    $this->layout('layout');
?>
<div class="topmenu">Chỉnh sửa bài viết #<?=$position?></div>
<?php if ($preview_mode): ?>
    <div class="post reply-preview"><div class="postbody"><div class="content"><?=$preview_post?></div></div><!-- END .postbody --></div>
<?php endif ?>
<div class="menu">
    <form name="form" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
        <p><textarea rows="<?=$user['field_h']?>" name="msg"><?=$input_message?></textarea></p>
        <?php if ($add_image_mode): ?>
            <?php if ($recent_images): ?>
                <p><b>Chọn ảnh gần đây</b></p><p class="recent-images">';
                <?php foreach($recent_images as $image): ?>
                    <a href="javascript:tag('[img]<?=$image['img_link']?>', '[/img]\r\n')" tabindex="-1"><img src="<?=$image['img_thumb']?>" /></a>
                <?php endforeach ?>
                </p>
            <?php endif ?>
            <p><b>Upload ảnh mới</b></p><p><input type="file" name="image" accept=".png, .jpg, .jpeg" /></p>
        <?php endif ?>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>" style="width: 79px; cursor: pointer;"/> <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" style="width: 79px;"/> <input type="submit" name="preview" value="<?=$lang['preview']?>" style="width: 79px; cursor: pointer;"/></p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>"/>
    </form>
</div>
<?php if ($add_image_mode): ?>
    <div class="notif">Sau khi chọn file, click "Đăng ảnh" để thêm ảnh vào bài viết</div>
<?php endif ?>