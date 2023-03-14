<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p>
            <h3><?=$lang['section']?></h3>
            <select name="razd"><?=$options?></select>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['move']?>"/></p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </div>
</form>