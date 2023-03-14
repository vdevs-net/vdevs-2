<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-heading"><h4 class="panel-title"><?=$lang['new_topic']?><?php if (!$show_rules && $preview_mode && $input_thread_name): ?>: <em><?=$input_thread_name?></em><?php endif?></div></h4>
<div class="panel-body">
<?php if ($show_rules): ?>
        <p><?=$lang['forum_rules_text']?></p>
        <hr/>
        <a href="<?=$agree_url?>" class="btn btn-success"><?=$lang['agree']?></a> <a href="<?=$deny_url?>" class="btn btn-danger margin-left"><?=$lang['not_agree']?></a>
<?php else: ?>
    <?php if ($preview_mode): ?>
        <div class="post"><div class="postbody"><div class="content"><?=$preview_post?></div></div><!-- END .postbody --></div>
        <hr />
    <?php endif ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif ?>
    <form name="form" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
        <div class="row">
                <div class="col-sm-3 col-xs-6"><select name="prefix" class="form-control"><?=$prefix_option?></select></div>
                <div class="col-sm-9 col-xs-6"><input type="text" size="20" maxlength="255" name="th" value="<?=$input_thread_name?>" class="form-control" placeholder="<?=$lang['new_topic_name']?>" /></div>
            </div>
        </div>
        <div class="form-group">
            <?=$bbcode_editor?>
            <textarea rows="10" name="msg" class="form-control" placeholder="<?=$lang['post']?>"><?=$input_post?></textarea>
        </div>
        <hr />
        <?php if ($add_image_mode): ?>
            <?php if ($recent_images): ?>
                <p><b>Chọn ảnh gần đây</b></p><p class="recent-images">
                <?php foreach ($recent_images as $image): ?>
                    <a href="javascript:tag('[img]<?=$image['img_link']?>', '[/img]\r\n')" tabindex="-1"><img src="<?=$image['img_thumb']?>" /></a>
                <?php endforeach ?>
                </p>
            <?php endif ?>
            <p><b>Upload ảnh mới</b></p><p><input type="file" name="image" accept=".png, .jpg, .jpeg" class="form-control" /></p>
            <hr />
        <?php endif ?>
        <div class="form-group">
            <input type="text" name="tags" autocomplete="off" value="<?=$input_tags?>" class="form-control" placeholder="Tags (phân cách bằng dấu phẩy)" />
        </div>
        <div class="form-group">
            <div class="checkbox"><label><input type="checkbox" name="addfiles" value="1"<?=$add_file_check?> /><?=$lang['add_file']?></label></div>
        </div>
        <?php if ($rights > RIGHTS_ADMIN): ?>
            <div class="form-group">
                <div class="checkbox"><label><input type="checkbox" name="portal" value="1"<?=$add_portal_check?> /> Add to Portal</label></div>
            </div>
        <?php endif ?>
        <div class="form-group">
            <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
            <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" class="btn btn-success" />
            <input type="submit" name="preview" value="<?=$lang['preview']?>" class="btn btn-info" />
        </div>
        <?php if ($add_image_mode): ?>
            <div class="alert alert-info">Sau khi chọn file, click "Đăng ảnh" để thêm ảnh vào bài viết</div>
        <?php endif ?>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php endif ?>
</div></div>