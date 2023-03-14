<?php
    $this->layout('layout');
?>
<form name="form" action="<?=$formAction?>" method="post">
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
    <div class="menu">
        <div><input type="text" name="name" value="<?=$inputName?>" /></div>
        <h3><?=$lang['add_dir_descriptions']?></h3>
        <div><textarea name="description" rows="4" cols="20"><?=$inputDescription?></textarea></div>
<?php if ($moveOptions): ?>
        <h3><?=$lang['move_dir']?></h3>
        <div><select name="move"><?=$moveOptions?></select></div>
<?php endif ?>
<?php if ($typeOptions): ?>
        <h3><?=$lang['category_type']?></h3>
        <div><select name="dir"><?=$typeOptions?></select></div>
<?php endif ?>
<?php if ($allowOptions): ?>
        <h3><?=$lang['allow_to_add']?></h3>
        <div><select name="user_add"><?=$allowOptions?></select></div>
<?php endif ?>
    </div>
    <div class="menu"><input type="submit" name="submit" value="<?=$lang['save']?>" /> <a class="btn" href="<?=$backUrl?>"><?=$lang['cancel']?></a></div>
</form>