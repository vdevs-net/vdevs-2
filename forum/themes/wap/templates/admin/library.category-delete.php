<?php
    $this->layout('layout');
?>
<div class="rmenu"><?=$warningText?></div>
<form action="<?=$formAction?>" method="post">
<div class="menu">
    <h3><?=$lang['move_dir']?></h3>
    <?php if ($destinations): ?>
        <?php foreach ($destinations as $destination): ?>
            <div><input type="radio" name="category" value="<?=$destination['id']?>" /> <?=$destination['name']?></div>
        <?php endforeach ?>
    <?php else: ?>
        <div><?=$lang['list_empty']?></div>
    <?php endif ?>
</div>
<div class="rmenu">Các chuyên mục con và bài viết sẽ được chuyển sang chuyên mục bạn chọn</div>
<div class="menu"><input type="submit" name="submit" value="<?=$lang['move']?>" /></div>
<?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
    <div class="rmenu">
        <p><h3>Loại bỏ hoàn toàn</h3>CẢNH BÁO! Lựa chọn này sẽ xóa bỏ tất cả chuyên mục con và bài viết trong chuyên mục</p>
        <p><input type="submit" name="delete" value="<?=$lang['delete']?>" /></p>
    </div>
<?php endif ?>
</form>