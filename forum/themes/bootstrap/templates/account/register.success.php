<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-heading"><?=$lang['you_registered']?></div>
<div class="panel-body">
    <p>
        <?=$lang['your_id']?>: <b><?=$registered_id?></b><br/>
        <?=$lang['your_login']?>: <b><?=$input_account?></b><br/>
        <?=$lang['your_password']?>: <b><?=$input_password?></b>
    </p>
    <p>
        <?php if ($need_activate): ?><span class="red">Tài khoản của bạn có thể sử dụng sau khi admin xác nhận. Vui lòng chờ.</span><?php else: ?><a href="<?=$site_path?>"><?=$lang['enter']?></a><?php endif ?>
    </p>
</div></div>
