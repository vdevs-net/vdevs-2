<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p><?=$confirm_text?></p>
        <p>
            <input type="submit" name="submit" value="<?=$lang['yes']?>" />
            <a href="<?=$cancel_url?>" class="btn cancel"><?=$lang['no']?></a>
        </p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>