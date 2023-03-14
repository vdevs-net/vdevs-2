<?php
    $this->layout('layout');
?>
<div class="panel panel-primary"><div class="panel-body">
<form action="<?=$form_action?>" method="post" class="form-horizontal noPusher">
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
        <?php if (count(core::$lng_list) > 1): ?>
            <?php foreach (core::$lng_list as $key => $val): ?>
                <label class="radio"><input type="radio" value="<?=$key?>" name="setlng"<?php if ($key == core::$lng_iso): ?> checked="checked"<?php endif ?> /> <img src="<?=SITE_URL?>/assets/images/flags/<?=$key?>.gif" alt="[<?=$key?>]"/> <?=$val?><?php if ($key == $set['lang']): ?> <small class="red">[<?=$lang['default']?>]</small><?php endif ?></label>
            <?php endforeach ?>
        <?php endif ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="<?=$lang['apply']?>" class="btn btn-primary" /></div>
    </div>
</form>
</div></div>