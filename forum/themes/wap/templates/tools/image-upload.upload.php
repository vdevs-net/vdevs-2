<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" enctype="multipart/form-data" method="post">
<?php if ($error) : ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
    <div class="menu">
    <?php if ($type == 'url'): ?>
        <input type="url" name="url" autocomplete="off" placeholder="Image URL" />
    <?php else: ?>
        <input type="file" name="file" accept="image/*" />
    <?php endif ?>
    </div>
    <div class="menu"><input type="submit" name="submit" value="Upload" /> <?php if ($type == 'url'): ?><a href="upload" class="btn">Upload file</a><?php else: ?><a href="upload?type=url" class="btn">Upload via URL</a><?php endif ?></div>
    <input type="hidden" name="token" value="<?=$token?>" />
</form>