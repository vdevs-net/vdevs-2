<?php
    $this->layout('layout');
?>
<form action="ban-panel?mod=amnesty" method="post"><div class="menu">
    <p>
        <input type="radio" name="term" value="0" checked="checked" /> <?=$lang['amnesty_delban']?><br />
        <input type="radio" name="term" value="1" /> <?=$lang['amnesty_clean']?>
    </p>
    <p><input type="submit" name="submit" value="<?=$lang['amnesty']?>" /></p>
</div></form>
<div class="notif"><small><?=$lang['amnesty_help']?></small></div>