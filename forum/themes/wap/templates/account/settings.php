<?php
    $this->layout('layout');
?>
<?php if ($set_ok): ?>
    <div class="gmenu"><?=$lang['settings_saved']?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" >
    <div class="menu">
        <p>
            <h3><?=$lang['system_functions']?></h3>
            <input name="direct_url" type="checkbox" value="1"<?php if ($set_user['direct_url']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['direct_url']?><br />
            <input name="smileys" type="checkbox" value="1"<?php if ($set_user['smileys']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['smileys']?><br/>
        </p>
        <p>
            <h3><?=$lang['text_input']?></h3>
            <input type="text" name="field_h" size="2" maxlength="1" value="<?=$set_user['field_h']?>"/> <?=$lang['field_height']?> (1-9)
        </p>
        <p>
            <h3><?=$lang['apperance']?></h3>
            <input type="text" name="kmess" size="2" maxlength="2" value="<?=$set_user['kmess']?>"/> <?=$lang['lines_on_page']?> (5-99)
        </p>
        <?php if (count($lang_list) > 1): ?>
            <p>
                <h3><?=$lang['language_select']?></h3>
                <?php foreach ($lang_list as $key => $val): ?>
                    <div><input type="radio" value="<?=$key?>" name="iso"<?php if ($key == $set_user['lng']): ?> checked="checked"<?php endif ?> />&#160;<img src="<?=$site_path?>/assets/images/flags/<?=$key?>.gif" alt=""/>&#160;<?=$val?><?php if ($key == $set['lang']): ?> <small class="red">[<?=$lang['default']?>]</small><?php endif ?></div>
                <?php endforeach ?>
            </p>
        <?php endif ?>
        <p>
            <h3><?=$lang['design_template']?></h3>
            <select name="theme"><?=$theme_options?></select>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>" /></p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>
