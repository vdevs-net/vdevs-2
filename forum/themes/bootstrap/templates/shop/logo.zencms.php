<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<div class="alert alert-info"><?=$description?></div>
<div class="well"><img src="<?=$logo_src?>" alt="logo" /></div>
<div class="center"><a href="<?=$logo_src?>" class="btn btn-primary btn-sm">Tải về</a></div>
<hr />
<form action="<?=$form_action?>" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="text1" class="col-sm-3 control-label">Tên miền</label>
        <div class="col-sm-9">
            <input type="text" name="text1" value="<?=$input_text1?>" autocomplete="off" class="form-control" id="text1" />
        </div>
    </div>
    <div class="form-group">
        <label for="text2" class="col-sm-3 control-label">Đuôi tên miền</label>
        <div class="col-sm-9">
            <input type="text" name="text2" value="<?=$input_text2?>" autocomplete="off" class="form-control" id="text2" />
        </div>
    </div>
    <div class="form-group">
        <label for="text3" class="col-sm-3 control-label">Văn bản</label>
        <div class="col-sm-9">
            <input type="text" name="text3" value="<?=$input_text3?>" autocomplete="off" class="form-control" id="text3" />
        </div>
    </div>
    <div class="form-group">
        <label for="position" class="col-sm-3 control-label">Vị trí văn bản theo chiều ngang</label>
        <div class="col-sm-9">
            <input type="text" name="position" value="<?=$input_position?>" autocomplete="off" class="form-control" id="position" />
        </div>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3"><input type="submit" value="Tạo Ngay" class="btn btn-primary" /></div>
    </div>
</form>
</div></div>