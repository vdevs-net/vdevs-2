<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary"><div class="panel-body">
<?php if ($error) : ?>
    <div class="alert alert-warning"><?=$error?></div>
<?php endif ?>
<form action="<?=$form_action?>" enctype="multipart/form-data" method="post">
    <div class="form-group">
    <?php if ($type == 'url'): ?>
        <input type="url" name="url" autocomplete="off" placeholder="Image URL" class="form-control" />
    <?php else: ?>
        <input type="file" name="file" accept="image/*" class="form-control" />
    <?php endif ?>
    </div>
    <hr />
    <div class="form-group"><button type="submit" name="submit" class="btn btn-primary" style="width:120px" id="upload">Tải lên</button> <?php if ($type == 'url'): ?><a href="upload" class="btn btn-success">Upload file</a><?php else: ?><a href="upload?type=url" class="btn btn-success">Upload via URL</a><?php endif ?></div>
    <input type="hidden" name="token" value="<?=$token?>" />
</form>
</div></div>