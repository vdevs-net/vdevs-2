<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-heading">Đính kèm tập tin vào bài viết #<?=$position?></div>
<div class="panel-body">
<form action="<?=$form_action?>" method="post" enctype="multipart/form-data">
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php endif ?>
    <div class="form-group">
        <input type="file" name="fail" class="form-control"/>
    </div>
    <div class="form-group">
        <input type="submit" name="submit" value="<?=$lang['upload']?>" class="btn btn-primary" />
        </div>
</form>
<div class="alert alert-info"><?=$form_description?></div>
</div></div>