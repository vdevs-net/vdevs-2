<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<div class="well"><img src="<?=$logo_src?>" alt="logo" /></div>
<div class="center"><a href="<?=$logo_src?>" class="btn btn-primary btn-sm">Tải về</a></div>
<hr/>
<form action="<?=$form_action?>" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="text" class="col-sm-3 control-label">Văn bản</label>
        <div class="col-sm-9">
            <input type="text" name="text" value="<?=$input_text?>" autocomplete="off" class="form-control" id="text1" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Tùy chọn</label>
        <div class="col-sm-9">
            <div class="radio"><label><input type="radio" name="style" value="1"<?php if ($input_style == 1): ?> checked="checked"<?php endif ?> /> Chữ trắng không nền</label></div>
            <div class="radio"><label><input type="radio" name="style" value="2"<?php if ($input_style == 2): ?> checked="checked"<?php endif ?> /> Chữ xanh dương không nền</label></div>
        </div>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3"><input type="submit" value="Tạo Ngay" class="btn btn-primary"/></div>
    </div>
</form>
</div></div>