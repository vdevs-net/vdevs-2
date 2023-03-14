<div class="panel panel-default margin-bottom">
<div class="profileCard radiusTop">
    <div class="profileCover radiusTop"<?php if ($user_cover_photo): ?> style="background-image:url('<?=$user_cover_photo?>')"<?php endif ?>>
        <?php if ($change_cover_url): ?><div class="changeCoverButton"><a href="<?=$change_cover_url?>"><i class="fa fa-camera fa-lg"></i><span class="changeText">Đổi ảnh bìa</span></a></div><?php endif ?>
    </div>
    <div class="profileInfo">
        <div class="profileName"><?=$user_name?></div>
        <?php if ($user_status): ?>
            <div class="profileStatus nowap"><?=$user_status?></div>
        <?php endif ?>
    </div>
    <?php if ($change_avatar_url): ?><a href="<?=$change_avatar_url?>"><div class="profileAvatar" style="background-image: url('<?=$user_avatar?>')"></div></a><?php else: ?><div class="profileAvatar" style="background-image: url('<?=$user_avatar?>')"></div><?php endif ?>
    <div class="profileMenu menu"><ul class="nav nav-pills nav-response"><li><?=(implode('</li><li class="pill">', $menu))?></li></ul></div>
</div>
</div>