<?php
    $this->layout('layout');
?>
<?php
    $this->insert('admin::usr.info', $userInfoVariable);
?>
<div class="phdr"><?=$lang['infringements_history']?></div>
<?php if ($success): ?>
<div class="gmenu"><?=$lang['history_cleared']?></div>
<?php else: ?>
<form action="<?=$formAction?>" method="post">
    <div class="menu">
        <p><?=$lang['clear_confirmation']?></p>
        <p><input type="submit" value="<?=$lang['clear']?>" name="submit" /></p>
    </div>
</form>
<div class="phdr"><?=$lang['total']?>: <?=$total?></div>
<?php if ($total): ?>
    <div class="menu"><a href="<?=$banHistoryUrl?>"><?=$lang['infringements_history']?></a></div>
<?php endif ?>
    <div class="menu"><a href="<?=$banPanelUrl?>"><?=$lang['ban_panel']?></a></div>
<?php endif ?>