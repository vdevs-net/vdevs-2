<?php
    $this->layout('layout');
?>
<form enctype="multipart/form-data" method="post" action="<?=$form_action?>">
    <div class="menu">
        <p><?=$lang['select_image']?><br /><input type="file" name="imagefile" accept="image/*" /></p>
        <p><label><input type="checkbox" name="delete" value="1" /> <?=$lang_delete?></label></p>
        <p><input type="submit" name="submit" value="<?=$lang['upload']?>" /></p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </div>
</form>
<div class="notif"><small><?=$form_help?></small></div>