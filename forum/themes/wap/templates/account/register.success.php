<?php
    $this->layout('layout');
?>
<div class="menu">
    <p>
        <h3><?=$lang['you_registered']?></h3>
        <?=$lang['your_id']?>: <b><?=$registered_id?></b><br/>
        <?=$lang['your_login']?>: <b><?=$input_account?></b><br/>
        <?=$lang['your_password']?>: <b><?=$input_password?></b>
    </p>
    <p>
        <?php if ($need_activate): ?><span class="red">Tài khoản của bạn có thể sử dụng sau khi admin xác nhận. Vui lòng chờ.</span><?php else: ?><a href="<?=$site_path?>"><?=$lang['enter']?></a><?php endif ?>
    </p>
</div>
