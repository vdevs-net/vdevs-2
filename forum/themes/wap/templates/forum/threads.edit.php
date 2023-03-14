<?php
    $this->layout('layout');
?>
<h3 class="menu"><?=$lang['topic_edit']?>: <em class="gray"><?=$thread_name?></em></h3>
<div class="menu">
    <form action="<?=$form_action?>" method="post">
        <p>
            <h3><?=$lang['topic_name']?></h3>
            <select name="prefix"><?=$prefix_option?></select>
            <input type="text" name="nn" value="<?=$thread_name?>" autocomplete="off"/>
        </p>
        <p>
            <h3>Tags</h3>
            <input type="text" name="tags" value="<?=$thread_tags?>" autocomplete="off"/>
        </p>
        <p>
            <input type="checkbox" name="close" value="1"<?php if ($thread_closed): ?> checked="checked"<?php endif ?> /> Khóa chủ đề
        </p>
        <p>
            <input type="checkbox" name="stick" value="1"<?php if ($thread_sticked): ?> checked="checked"<?php endif ?> /> Ghim chủ đề
        </p>
            <?php if ($rights >= RIGHTS_ADMIN): ?><p><input type="checkbox" name="portal" value="1"<?php if ($thread_portal): ?> checked="checked"<?php endif ?> /> Thêm vào trang chủ</p><?php endif ?>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>"/></p>
        <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>