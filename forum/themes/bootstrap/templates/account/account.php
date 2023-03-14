<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
    <div class="list-group">
        <a class="list-group-item" href="settings"><?=$lang['settings']?></a>
        <a class="list-group-item" href="password"><?=$lang['change_password']?></a>
        <a class="list-group-item" href="recover"><?=$lang['forgotten_password']?></a>
        <a class="list-group-item" href="ip"><?=$lang['ip_history']?></a>
    </div>
    <div class="panel-body">
        <form action="<?=$form_action?>" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="status" class="col-sm-3 control-label"><?=$lang['status']?></label>
                <div class="col-sm-9">
                    <input type="text" value="<?=$user_status?>" name="status" id="status" class="form-control" />
                </div>
            </div>
            <hr class="separator" />
            <div class="form-group">
                <label class="col-sm-3 control-label"><?=$lang['avatar']?></label>
                <div class="col-sm-9">
                    <img src="<?=$user_avatar?>" width="64" height="64" alt="<?=$user_account?>" />
                    <span class="help-block"><a href="<?=$edit_avatar_url?>"><?=$lang['upload']?></a></span>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label"><?=$lang['name']?></label>
                <div class="col-sm-9">
                    <input type="text" name="imname" maxlength="32" value="<?=$user_name?>" id="name" class="form-control"  />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-3 control-label"><?=$lang['gender']?></label>
                <div class="col-sm-9">
                    <label class="checkbox-inline"><input type="radio" value="m" name="sex"<?php if($user_sex == 'm'): ?> checked="checked"<?php endif ?> /> <?=$lang['sex_m']?></label><label class="checkbox-inline"><input type="radio" value="f" name="sex"<?php if($user_sex == 'f'): ?> checked="checked"<?php endif ?> /> <?=$lang['sex_w']?></label>
                    </div>
            </div>
            <div class="form-group date-input">
                <label for="birthDate" class="col-sm-3 control-label"><?=$lang['birth_date']?></label>
                <div class="col-sm-9">
                    <input type="text" value="<?php if ($user_yearb): ?><?=$user_dayb?>/<?=$user_monthb?>/<?=$user_yearb?><?php endif ?>" class="form-control" id="birthDate" />
                </div>
                <input type="hidden" value="<?=$user_dayb?>" maxlength="2" name="dayb" id="dateDay" />
                <input type="hidden" value="<?=$user_monthb?>" maxlength="2" name="monthb" id="dateMonth" />
                <input type="hidden" value="<?=$user_yearb?>" maxlength="4" name="yearb" id="dateYear" />
            </div>
            <div class="form-group">
                <label for="live" class="col-sm-3 control-label"><?=$lang['city']?></label>
                <div class="col-sm-9">
                    <input type="text" value="<?=$user_live?>" name="live" id="live" class="form-control"  />
                </div>
            </div>
            <div class="form-group">
                <label for="about" class="col-sm-3 control-label"><?=$lang['about']?></label>
                <div class="col-sm-9">
                    <textarea rows="<?=$user['field_h']?>" name="about" id="about" class="form-control"><?=$user_about?></textarea>
                </div>
            </div>
            <hr class="separator" />
            <div class="form-group">
                <label class="col-sm-3 control-label"><?=$lang['phone_number']?></label>
                <div class="col-sm-9">
                    <input type="text" value="<?=$user_mobile?>" disabled="disabled" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-3 control-label">E-mail</label>
                <div class="col-sm-9">
                    <input type="text" value="<?=$user_mail?>" name="mail" class="form-control" id="email" />
                    <span class="help-block"><?=$lang['email_warning']?></span>
                    <div class="checkbox"><label><input name="mailvis" type="checkbox" value="1"<?=$user_mailvis?> /> <?=$lang['show_in_profile']?></label></div>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">Mật khẩu hiện tại</label>
                <div class="col-sm-9">
                    <input type="password" name="password" class="form-control" id="password" />
                    <span class="help-block">Cần nhập nếu bạn muốn thay đổi email!</span>
                </div>
            </div>
            <div class="form-group">
                <label for="facebook" class="col-sm-3 control-label">Facebook</label>
                <div class="col-sm-9">
                    <input type="text" value="<?=$user_facebook?>" name="facebook" class="form-control" id="facebook" />
                </div>
            </div>
            <hr class="separator" />
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <input type="submit" value="<?=$lang['save']?>" name="submit" class="btn btn-primary" />
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        </form>
    </div>
</div>
