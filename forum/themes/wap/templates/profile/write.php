<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post" name="form">
<div class="phdr"><?=$form_title?></div>
<?php if($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<div class="menu">
    <p><textarea name="text" rows="<?=$user['field_h']?>" required="required"></textarea></p>
</div>
<div class="topmenu"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="50%"><select name="privacy"><?=$privacy_option?></select></td><td width="50%" style="text-align:right"><input type="submit" name="submit" value="<?=$lang['write']?>" /></td></tr></table></div>
<input type="hidden" name="token" value="<?=$token?>" />
</form>