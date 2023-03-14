<?php
    $this->layout('layout');
?>
<div class="menu">
    <form  method="post" action="<?=$form_action?>">
        <p>
            <h3><?=$lang['clear_param']?></h3>
            <input type="radio" name="cl" value="0" checked="checked" /><?=$lang['clear_month']?><br />
            <input type="radio" name="cl" value="1" /><?=$lang['clear_week']?><br />
            <input type="radio" name="cl" value="2" /><?=$lang['clear_all']?>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['clear']?>" /></p>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>