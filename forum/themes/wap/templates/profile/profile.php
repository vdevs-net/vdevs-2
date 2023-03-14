<?php
    $this->layout('layout');
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<?php if ($is_birthday): ?>
    <div class="gmenu"><?=$lang['birthday']?></div>
<?php endif ?>
<?php if ($not_activated): ?>
    <div class="notif"><?=$lang['awaiting_registration']?></div>
<?php endif ?>
<div class="phdr"><?=$form_title?></div>
<form action="<?=$form_action?>" method="post" name="form">
    <div class="menu">
        <p><textarea name="text" rows="<?=$user['field_h']?>" required="required"></textarea></p>
    </div>
    <div class="topmenu"><table width="100%" cellpadding="0" cellspacing="0"><tr><td width="50%"><select name="privacy"><option value="0">Mọi người</option><option value="2">Chỉ mình tôi</option></select></td><td width="50%" style="text-align:right"><input type="submit" name="submit" value="<?=$lang['write']?>" /></td></tr></table></div>
    <input type="hidden" name="token" value="<?=$token?>" />
</form>
<?php if ($posts): ?>
    <?php foreach ($posts as $post): ?>
        <div class="menu">
            <table width="100%"><tr>
                <td width="36px"><img src="<?=$post['user_avatar']?>" width="32" height="32" alt="<?=$post['user_name']?>"></td>
                <td><div><a href="<?=$post['user_profile_url']?>" class="<?=$post['user_html_class']?>"><?=$post['user_name']?></a></div><div><?=$post['time']?> &middot; <?=$post['privacy']?></div></td>
            </tr></table>
            <div class="text"><?=$post['text']?></div>
            <?php if ($post['edit_url'] || $post['delete_url']): ?>
                <div class="sub"><?php if ($post['edit_url']): ?><a href="<?=$post['edit_url']?>"><?=$lang['edit']?></a><?php endif ?><?php if ($post['delete_url']): ?> &middot; <a href="<?=$post['delete_url']?>"><?=$lang['delete']?></a><?php endif ?></div>
            <?php endif ?>
        </div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php endif ?>