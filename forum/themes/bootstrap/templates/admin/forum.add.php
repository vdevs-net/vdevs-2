<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($isAddForum): ?>
    <div class="bmenu"><b><?=$lang['to_category']?>:</b> <a href="<?=$categoryUrl?>"><?=$categoryName?></a></div>
<?php endif ?>
<form action="<?=$formAction?>" method="post">
<div class="gmenu">
    <p><h3><?=$lang['title']?></h3><input type="text" name="name" /><br /><small><?=$lang['minmax_2_30']?></small></p>
    <p><h3><?=$lang['description']?></h3><textarea name="desc" rows="<?=$user['field_h']?>"></textarea><br /><small><?=$lang['not_mandatory_field']?><br /><?=$lang['minmax_2_500']?></small></p>
    <?php if ($isAddForum): ?>
        <div><input type="radio" name="allow" value="0" checked="checked" /> <?=$lang['allow_plain']?></div>
        <div><input type="radio" name="allow" value="4" /> <?=$lang['allow_readonly']?></div>
        <div><input type="radio" name="allow" value="2" /> <?=$lang['allow_firstpost_edit']?></div>
    <?php endif ?>
    <p><input type="submit" value="<?=$lang['add']?>" name="submit" /></p>
</div>
</form>