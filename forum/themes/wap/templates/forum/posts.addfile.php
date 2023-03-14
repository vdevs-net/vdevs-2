<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post" enctype="multipart/form-data">
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
    <div class="menu">
        <div><input type="file" name="fail" /></div>
        <input type="submit" name="submit" value="<?=$lang['upload']?>"/>
    </div>
</form>
<div class="notif"><?=$form_description?></div>