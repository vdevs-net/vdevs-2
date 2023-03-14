<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($settings_saved): ?>
    <div class="gmenu"><?=$lang['settings_saved']?></div>
<?php elseif ($settings_default): ?>
    <div class="gmenu"><?=$lang['settings_default']?></div>
<?php endif ?>
<form action="antiflood" method="post">
    <div class="gmenu"><p><h3><?=$lang['operation_mode']?></h3><table cellspacing="2">
        <tr><td valign="top"><input type="radio" name="mode" value="3"<?php if($set_af['mode'] == 3): ?> checked="checked"<?php endif ?> /></td><td><b><?=$lang['day']?></b></td></tr>
        <tr><td valign="top"><input type="radio" name="mode" value="4"<?php if($set_af['mode'] == 4): ?> checked="checked"<?php endif ?> /></td><td><b><?=$lang['night']?></b></td></tr>
        <tr><td valign="top"><input type="radio" name="mode" value="2"<?php if($set_af['mode'] == 2): ?> checked="checked"<?php endif ?> /></td><td><b><?=$lang['day']?> / <?=$lang['night']?></b><br /><small><?=$lang['antiflood_dn_help']?></small></td></tr>
        <tr><td valign="top"><input type="radio" name="mode" value="1"<?php if($set_af['mode'] == 1): ?> checked="checked"<?php endif ?> /></td><td><b><?=$lang['adaptive']?></b><br /><small><?=$lang['antiflood_ad_help']?></small></td></tr>
    </table></p></div>
    <div class="menu">
        <p><h3><?=$lang['time_limit']?></h3>
            <input name="day" size="3" value="<?=$set_af['day']?>" maxlength="3" />&#160;<?=$lang['day']?><br />
            <input name="night" size="3" value="<?=$set_af['night']?>" maxlength="3" />&#160;<?=$lang['night']?><br /><small><?=$lang['antiflood_tl_help']?></small>
        </p>
        <p><h3><?=$lang['day_mode']?></h3>
            <input name="dayfrom" size="2" value="<?=$set_af['dayfrom']?>" maxlength="2" style="text-align:right"/>:00&#160;<?=$lang['day_begin']?> <span class="gray">(6-12)</span><br />
            <input name="dayto" size="2" value="<?=$set_af['dayto']?>" maxlength="2" style="text-align:right"/>:00&#160;<?=$lang['day_end']?> <span class="gray">(17-23)</span>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>"/>&nbsp;<a class="btn" href="antiflood?reset"><?=$lang['reset_settings']?></a></p>
    </div>
</form>