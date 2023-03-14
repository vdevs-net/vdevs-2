<?php
    $this->layout('layout');
?>
<?php if ($show_rules): ?>
    <div class="menu">
        <p><?=$lang['forum_rules_text']?></p>
        <a href="<?=$agree_url?>"><?=$lang['agree']?></a> | <a href="<?=$deny_url?>"><?=$lang['not_agree']?></a>
    </div>
<?php else: ?>
    <div class="topmenu">Trích dẫn bài viết #<?=$position?> của <b><?=$post_author?></b></div>
    <?php if ($preview_mode): ?>
        <div class="post reply-preview"><div class="postbody"><div class="content"><?=$preview_post?></div></div><!-- END .postbody --></div>
    <?php endif ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <form name="form" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
        <div class="menu">
            <p>
                <h3><?=$lang['cytate']?></h3>
                <textarea rows="<?=$user['field_h']?>" name="citata"><?=$input_quote?></textarea>
            </p>
            <h3><?=$lang['post']?></h3>
            <p><textarea rows="<?=$user['field_h']?>" name="msg"><?=$input_message?></textarea></p>
            <?php if ($add_image_mode): ?>
                <?php if ($recent_images): ?>
                    <p><b>Chọn ảnh gần đây</b></p><p class="recent-images">
                    <?php foreach($recent_images as $image): ?>
                        <a href="javascript:tag('[img]<?=$image['img_link']?>', '[/img]\r\n')" tabindex="-1"><img src="<?=$image['img_thumb']?>" /></a>
                    <?php endforeach ?>
                    </p>
                <?php endif ?>
                <p><b>Upload ảnh mới</b></p><p><input type="file" name="image" accept=".png, .jpg, .jpeg" /></p>
            <?php endif ?>
            <label class="checkbox"><input type="checkbox" name="addfiles" value="1"<?=$add_file_check?> /> <?=$lang['add_file']?></label>
            <p><input type="submit" name="submit" value="<?=$lang['sent']?>" style="width: 79px; cursor: pointer;"/> <input type="submit" name="add_image" value="<?=$lang['upload_photo']?>" style="width: 79px;"/> <input type="submit" name="preview" value="<?=$lang['preview']?>" style="width: 79px; cursor: pointer;"/></p>
        </div>
        <?php if ($add_image_mode): ?>
            <div class="rmenu">Sau khi chọn file, click "Đăng ảnh" để thêm ảnh vào bài viết</div>
        <?php endif ?>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php endif ?>