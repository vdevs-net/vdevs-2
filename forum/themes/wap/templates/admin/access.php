<?php
    $this->layout('layout');
?>
<?php if ($settings_saved): ?>
    <div class="gmenu"><?=$lang['settings_saved']?></div>
<?php endif ?>
<form method="post" action="access">
    <div class="menu">
    <p><h3><?=$lang['forum']?></h3><div style="font-size: x-small">
        <input type="radio" value="2" name="forum"<?php if ($set_mod_forum == 2): ?> checked="checked"<?php endif ?> /> <?=$lang['access_enabled']?><br />
        <input type="radio" value="1" name="forum"<?php if ($set_mod_forum == 1): ?> checked="checked"<?php endif ?> /> <?=$lang['access_authorised']?><br />
        <input type="radio" value="3" name="forum"<?php if ($set_mod_forum == 3): ?> checked="checked"<?php endif ?> /> <?=$lang['read_only']?><br />
        <input type="radio" value="0" name="forum"<?php if (!$set_mod_forum): ?> checked="checked"<?php endif ?> /> <?=$lang['access_disabled']?>
    </div></p>
    <p><h3><?=$lang['community']?></h3><div style="font-size: x-small">
        <input type="radio" value="1" name="active"<?php if ($set_active): ?> checked="checked"<?php endif ?> /> <?=$lang['access_enabled']?><br />
        <input type="radio" value="0" name="active"<?php if (!$set_active): ?> checked="checked"<?php endif ?> /> <?=$lang['access_authorised']?>
    </div></p>
    </div>
    <div class="gmenu"><h3><?=$lang['registration']?></h3><div style="font-size: x-small">
        <input type="radio" value="2" name="reg"<?php if ($set_mod_reg == 2): ?> checked="checked"<?php endif ?> /> <?=$lang['access_enabled']?><br />
        <input type="radio" value="1" name="reg"<?php if ($set_mod_reg == 1): ?> checked="checked"<?php endif ?> /> <?=$lang['access_with_moderation']?><br />
        <input type="radio" value="0" name="reg"<?php if (!$set_mod_reg): ?> checked="checked"<?php endif ?> /> <?=$lang['access_disabled']?>
    </div></div>
    <div class="rmenu"><h3><?=$lang['site_access']?></h3><div style="font-size: x-small">
        <input type="radio" value="2" name="access"<?php if ($set_site_access == 2): ?> checked="checked"<?php endif ?> /> <?=$lang['access_enabled']?><br />
        <input type="radio" value="1" name="access"<?php if ($set_site_access == 1): ?> checked="checked"<?php endif ?> /> <?=$lang['site_closed_except_adm']?><br />
        <input type="radio" value="0" name="access"<?php if (!$set_site_access): ?> checked="checked"<?php endif ?> /> <?=$lang['site_closed_except_sv']?>
    </div></div>
    <div class="phdr"><small><?=$lang['access_help']?></small></div>
    <div class="menu"><input type="submit" name="submit" id="button" value="<?=$lang['save']?>" /></div>
</form>