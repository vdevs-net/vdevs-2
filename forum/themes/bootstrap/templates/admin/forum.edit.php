<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<form action="<?=$formAction?>" method="post">
<div class="gmenu">
    <p><h3><?=$lang['title']?></h3><input type="text" name="name" value="<?=$nameInput?>" /><br /><small><?=$lang['minmax_2_30']?></small></p>
    <p><h3><?=$lang['description']?></h3><textarea name="desc" rows="<?=$user['field_h']?>"><?=$descInput?></textarea><br /><small><?=$lang['not_mandatory_field']?><br /><?=$lang['minmax_2_500']?></small></p>
    <?php if ($isEditForum): ?>
        <div><input type="radio" name="allow" value="0"<?php if (!$forumAllow): ?> checked="checked"<?php endif ?> /> <?=$lang['allow_plain']?></div>
        <div><input type="radio" name="allow" value="4"<?php if ($forumAllow == 4): ?> checked="checked"<?php endif ?> /> <?=$lang['allow_readonly']?></div>
        <div><input type="radio" name="allow" value="2"<?php if ($forumAllow == 2): ?> checked="checked"<?php endif ?> /> <?=$lang['allow_firstpost_edit']?></div>
        <p><h3><?=$lang['category']?></h3><select name="category">
        <?php foreach ($categoryList as $category): ?>
            <option value="<?=$category['id']?>" <?=$category['selectStatus']?>><?=$category['name']?></option>
        <?php endforeach ?>
        </select></p>
    <?php endif ?>
    <p><input type="submit" value="<?=$lang['save']?>" name="submit" /></p>
</div>
</form>