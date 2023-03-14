<?php
    $this->layout('layout');
?>
<div class="menu">
<form action="<?=$form_action?>" method="post">
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        <h4>Are you sure?</h4>
        <p>
            <input type="submit" name="submit" value="<?=$lang['yes']?>" class="btn btn-primary" />
            <a href="<?=$cancel_url?>" class="btn btn-danger"><?=$lang['no']?></a>
        </p>
</form>
</div>