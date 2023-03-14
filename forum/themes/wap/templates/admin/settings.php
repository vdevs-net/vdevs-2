<?php
    $this->layout('layout');
?>
<?php if ($settings_saved): ?>
    <div class="rmenu"><?=$lang['settings_saved']?></div>
<?php endif ?>
<form action="settings" method="post"><div class="menu">
    <div><h3><?=$lang['common_settings']?></h3>
    <p><?=$lang['site_copyright']?>:<br/><input type="text" name="copyright" value="<?=$set_copyright?>"/></p>
    <p><?=$lang['site_email']?>:<br/><input name="madm" maxlength="50" value="<?=$set_email?>"/></p>
    <p><?=$lang['file_maxsize']?> (kb):<br /><input type="text" name="flsz" value="<?=$set_flsz?>"/></p>
    <p><input name="gz" type="checkbox" value="1"<?php if ($set_gzip): ?> checked="checked"<?php endif ?> /> <?=$lang['gzip_compress']?></p>
    </div>
    <div><h3><?=$lang['meta_tags']?></h3>
        <p><?=$lang['meta_keywords']?>:<br /><textarea rows="<?=$user['field_h']?>" name="meta_key"><?=$set_meta_key?></textarea></p>
        <p><?=$lang['meta_description']?>:<br /><textarea rows="<?=$user['field_h']?>" name="meta_desc"><?=$set_meta_desc?></textarea></p>
    </div>
    <div><h3><?=$lang['design_template']?></h3>
    <p>Giao diện mặc định Mobile<br /><select name="theme_wap">
    <?php foreach ($theme_list as $theme): ?>
        <option value="<?=$theme?>"<?php if($set_theme_wap == $theme): ?> selected="selected"<?php endif ?>><?=$theme?></option>
    <?php endforeach ?></select></p>
    <p>Giao diện mặc định Touch<br /><select name="theme_touch">
    <?php foreach ($theme_list as $theme): ?>
        <option value="<?=$theme?>"<?php if($set_theme_touch == $theme): ?> selected="selected"<?php endif ?>><?=$theme?></option>
    <?php endforeach ?></select></p>
    <p>Giao diện mặc định Web<br /><select name="theme_web">
    <?php foreach ($theme_list as $theme): ?>
        <option value="<?=$theme?>"<?php if($set_theme_web == $theme): ?> selected="selected"<?php endif ?>><?=$theme?></option>
    <?php endforeach ?></select>
    </p></div>
    <p><input type="submit" name="submit" value="<?=$lang['save']?>"/></p>
    </div>
</form>