<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post" name="form">
<div class="phdr"><?=$form_title?></div>
<?php if($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<div class="menu">
    <p><textarea name="text" rows="<?=$user['field_h']?>" required="required"><?=$input_text?></textarea></p>
</div>
<div class="topmenu"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="50%"><select name="privacy"><option value="0">Mọi người</option><option value="2">Chỉ mình tôi</option></select></td><td width="50%" style="text-align:right"><input type="submit" name="submit" value="<?=$lang['write']?>" /></td></tr></table></div>
<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>