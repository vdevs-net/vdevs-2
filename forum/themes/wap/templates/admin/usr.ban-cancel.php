<?php
    $this->layout('layout');
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$title?></div>
<?php if ($success): ?>
    <div class="gmenu"><?=$successText?></div>
<?php else: ?>
    <form action="<?=$formAction?>" method="POST">
        <div class="menu">
            <p><?=$formAlert?></p>
            <p><input type="submit" name="submit" value="<?=$submitText?>" /></p>
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php endif ?>