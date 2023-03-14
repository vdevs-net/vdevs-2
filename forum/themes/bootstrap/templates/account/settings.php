<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <?php if ($set_ok): ?>
        <div class="alert alert-success"><?=$lang['settings_saved']?></div>
    <?php endif ?>
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label"><?=$lang['system_functions']?></label>
            <div class="col-sm-9">
                <div class="checkbox"><label><input name="direct_url" type="checkbox" value="1"<?php if ($set_user['direct_url']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['direct_url']?></label></div>
                <div class="checkbox"><label><input name="smileys" type="checkbox" value="1"<?php if ($set_user['smileys']): ?> checked="checked"<?php endif ?> />&#160;<?=$lang['smileys']?></label></div>
            </div>
        </div>
        <div class="form-group">
            <label for="field_h" class="col-sm-3 control-label"><?=$lang['text_input']?></label>
            <div class="col-sm-9">
                <input type="text" name="field_h" maxlength="1" value="<?=$set_user['field_h']?>" class="form-control" id="field_h" />
                <span class="help-block"><?=$lang['field_height']?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="kmess" class="col-sm-3 control-label"><?=$lang['apperance']?></label>
            <div class="col-sm-9">
                <input type="text" name="kmess" maxlength="2" value="<?=$set_user['kmess']?>" class="form-control" id="kmess" />
                <span class="help-block"><?=$lang['lines_on_page']?></span>
            </div>
        </div>
        <?php if (count($lang_list) > 1): ?>
        <div class="form-group">
            <label class="col-sm-3 control-label"><?=$lang['language_select']?></label>
            <div class="col-sm-9">
            <?php foreach ($lang_list as $key => $val): ?>
                <div class="checkbox"><label><input type="radio" value="<?=$key?>" name="iso"<?php if ($key == $set_user['lng']): ?> checked="checked"<?php endif ?> />&#160;<img src="<?=$site_path?>/assets/images/flags/<?=$key?>.gif" alt=""/>&#160;<?=$val?><?php if ($key == $set['lang']): ?> <small class="red">[<?=$lang['default']?>]</small><?php endif ?></label></div>
            <?php endforeach ?>
            </div>
        </div>
        <?php endif ?>
        <div class="form-group">
            <label class="col-sm-3 control-label"><?=$lang['design_template']?></label>
            <div class="col-sm-9">
                <select name="theme" class="form-control"><?=$theme_options?></select>
            </div>
        </div>
        <hr class="separator" />
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" />
            </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>
</div>
