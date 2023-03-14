<?php
    $this->layout('layout');
?>
<div class="user"><p><?=$this_user_info?></p></div>
<form action="<?=$formAction?>" method="post"><div class="menu">
    <p><h3><?=$lang['user_del_activity']?></h3>
    <?php if ($forumt_count || $forump_count): ?>
        <div><input type="checkbox" value="1" name="forum" checked="checked" />&#160;<?=$lang['forum']?> <span class="red">(<?=$forumt_count?>&nbsp;/&nbsp;<?=$forump_count?>)</span></div>
        <small><span class="gray"><?=$lang['user_del_forumnote']?></span></small>
    <?php endif ?>
    </p></div>
    <div class="rmenu"><p><?=$lang['user_del_confirm']?></p>
    <p><input type="submit" value="<?=$lang['delete']?>" name="submit" /></p>
</div></form>
<div class="phdr"><a href="<?=$profileUrl?>"><?=$lang['to_form']?></a></div>
<div class="menu"><a href="usr"><?=$lang['users_list']?></a></div>