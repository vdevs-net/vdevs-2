<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-heading">Trích dẫn bài viết #<?=$position?> của <b><?=$post_author?></b></div>
<div class="panel-body">
<?php if ($show_rules): ?>
        <p><?=$lang['forum_rules_text']?></p>
        <hr/>
        <a href="<?=$agree_url?>" class="btn btn-success"><?=$lang['agree']?></a> <a href="<?=$deny_url?>" class="btn btn-danger margin-left"><?=$lang['not_agree']?></a>
<?php else: ?>
    <?php if ($preview_mode): ?>
        <div class="post reply-preview"><div class="postbody"><div class="content"><?=$preview_post?></div></div><!-- END .postbody --></div>
        <hr />
    <?php endif ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif ?>
    <form name="form" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="quote" class="control-label"><?=$lang['cytate']?></label>
            <textarea rows="<?=$user['field_h']?>" name="citata" class="form-control" id="quote"><?=$input_quote?></textarea>
        </div>
        <div class="form-group">
            <label for="message" class="control-label"><?=$lang['post']?></label>
            <?=$bbcode_editor?>
            <p><textarea rows="10" name="msg" class="form-control" id="message"><?=$input_message?></textarea></p>
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
            <div class="checkbox"><label><input type="checkbox" name="addfiles" value="1"<?=$add_file_check?> /> <?=$lang['add_file']?></label></div>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="<?=$lang['sent']?>" class="btn btn-primary" />
            <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" class="btn btn-success margin-left" />
            <input type="submit" name="preview" value="<?=$lang['preview']?>" class="btn btn-info margin-left" />
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
    <?php if ($add_image_mode): ?>
        <div class="alert alert-info">Sau khi chọn file, click "Đăng ảnh" để thêm ảnh vào bài viết</div>
    <?php endif ?>
<?php endif ?>
</div>
</div>