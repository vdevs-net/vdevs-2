<?php
    $this->layout('layout');
?>
<div class="gmenu">
    <form action="<?=$search_user_form_action?>" method="get">
        <p>
            <h3><?=$lang['search']?></h3>
            <div><input type="text" name="q" /> <input type="submit" value="<?=$lang['search']?>" /></div>
            <small><?=$lang['search_nick_help']?></small>
        </p>
    </form>
</div>
<div class="menu"><a href="userlist"><?=$lang['users']?></a> (<?=$count_users?>)</div>
<div class="menu"><a href="userlist?type=staff"><?=$lang['administration']?></a> (<?=$count_admin?>)</div>
<?php if ($count_birth): ?>
    <div class="menu"><a href="userlist?type=birthday"><?=$lang['birthday_men']?></a> (<?=$count_birth?>)</div>
<?php endif ?>
<div class="menu"><a href="top"><?=$lang['users_top']?></a></div>