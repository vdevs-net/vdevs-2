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

<form action="news" method="post"><div class="menu">
    <p><h3><?=$lang['apperance']?></h3>
        <input type="radio" value="1" name="view"<?php if ($settings['view'] == 1): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['heading_and_text']?><br />
        <input type="radio" value="2" name="view"<?php if ($settings['view'] == 2): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['heading']?><br />
        <input type="radio" value="3" name="view"<?php if ($settings['view'] == 3): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['text']?><br />
        <input type="radio" value="0" name="view"<?php if (!$settings['view']): ?> checked="checked"<?php endif ?> />&#160;<b><?=$lang['dont_display']?></b>
    </p>
    <p>
        <input name="breaks" type="checkbox" value="1"<?php if ($settings['breaks']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['line_foldings']?><br />
        <input name="smileys" type="checkbox" value="1"<?php if ($settings['smileys']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['smileys']?><br />
        <input name="tags" type="checkbox" value="1"<?php if ($settings['tags']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['bbcode']?><br />
        <input name="kom" type="checkbox" value="1"<?php if ($settings['kom']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['comments']?>
    </p>
    <p><h3><?=$lang['text_size']?></h3><input type="text" size="3" maxlength="3" name="size" value="<?=$settings['size']?>" />&#160;(50 - 500)</p>
    <p><h3><?=$lang['news_count']?></h3><input type="text" size="3" maxlength="2" name="quantity" value="<?=$settings['quantity']?>" />&#160;(1 - 15)</p>
    <p><h3><?=$lang['news_howmanydays_display']?></h3><input type="text" size="3" maxlength="2" name="days" value="<?=$settings['days']?>" />&#160;(0 - 15)<br /><small>0 - <?=$lang['without_limit']?></small></p>
    <p><input type="submit" value="<?=$lang['save']?>" name="submit" />&nbsp;<a href="news?reset" class="btn"><?=$lang['reset_settings']?></a></p>
</div></form>