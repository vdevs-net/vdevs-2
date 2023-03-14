<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <?php if (count(core::$lng_list) > 1): ?>
            <?php foreach (core::$lng_list as $key => $val): ?>
                <div><label class="radio"><input type="radio" value="<?=$key?>" name="setlng"<?php if ($key == core::$lng_iso): ?> checked="checked"<?php endif ?> /> <img src="<?=SITE_URL?>/assets/images/flags/<?=$key?>.gif" alt="[<?=$key?>]"/> <?=$val?><?php if ($key == $set['lang']): ?> <small class="red">[<?=$lang['default']?>]</small><?php endif ?></label></div>
            <?php endforeach ?>
        <?php endif ?>
        <p><input type="submit" name="submit" value="<?=$lang['apply']?>" /></p>
    </div>
</form>