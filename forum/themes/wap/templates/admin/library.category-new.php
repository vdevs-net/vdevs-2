<?php
    $this->layout('layout');
?>
<div class="bmenu"><b>Vào chuyên mục:</b> <a href="<?=$categoryUrl?>"><?=$categoryName?></a></div>
<form action="<?=$formAction?>" method="post">
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
    <div class="menu">
        <h3><?=$lang['title']?>:</h3>
        <div><input type="text" name="name" value="<?=$inputName?>" /></div>
        <h3><?=$lang['add_dir_descriptions']?>:</h3>
        <div><textarea name="description" rows="4" cols="20" value="<?=$inputDescription?>"></textarea></div>
        <h3><?=$lang['category_type']?></h3>
        <div><select name="type">
            <option value="1"><?=$lang['categories']?></option>
            <option value="0"><?=$lang['articles']?></option>
        </select></div>
        <div><?=$lang['allow_to_add']?></div>
        <div><input type="radio" name="user_add" value="0"<?php if ($inputUserAdd == 0): ?> checked="checked"<?php endif ?> /> <?=$lang['_no']?></div>
        <div><input type="radio" name="user_add" value="1"<?php if ($inputUserAdd == 1): ?> checked="checked"<?php endif ?> /> <?=$lang['_yes']?></div>
        <div><input type="submit" name="submit" value="<?=$lang['save']?>"/></div>
    </div>
</form>