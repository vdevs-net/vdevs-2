<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title"><?=$lang['topic_edit']?>: <em class="gray"><?=$thread_name?></em></h3></div>
<div class="panel-body">
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-3">Tiền tố</label>
            <div class="col-sm-9">
                <select name="prefix" class="form-control"><?=$prefix_option?></select>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="control-label col-sm-3"><?=$lang['topic_name']?></label>
            <div class="col-sm-9">
                <input type="text" name="nn" value="<?=$thread_name?>" autocomplete="off" class="form-control" id="name" />
            </div>
        </div>
        <div class="form-group">
            <label for="tags" class="control-label col-sm-3">Tags</label>
            <div class="col-sm-9">
                <input type="text" name="tags" value="<?=$thread_tags?>" autocomplete="off" class="form-control" placeholder="Tags" id="tags" />
            </div>
        </div>
        <hr/>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <div class="checkbox"><label><input type="checkbox" name="close" value="1"<?php if ($thread_closed): ?> checked="checked"<?php endif ?> /> Khóa chủ đề</label></div>
                <div class="checkbox"><label><input type="checkbox" name="stick" value="1"<?php if ($thread_sticked): ?> checked="checked"<?php endif ?> /> Ghim chủ đề</label></div>
                <?php if ($rights >= RIGHTS_ADMIN): ?><div class="checkbox"><label><input type="checkbox" name="portal" value="1"<?php if ($thread_portal): ?> checked="checked"<?php endif ?> /> Thêm vào trang chủ</label></div><?php endif ?>
            </div>
        </div>
        <hr />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
            </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>
</div>