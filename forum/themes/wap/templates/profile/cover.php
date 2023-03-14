<div class="profileCard">
    <div class="profileCover"<?php if ($user_cover_photo): ?> style="background-image:url('<?=$user_cover_photo?>')"<?php endif ?>><div class="profileInfo"><span><?=$user_name?></span></div></div>
    <div class="profileAvatar" style="background-image: url('<?=$user_avatar?>')"></div>
    <div class="profileMenu menu"><?=(implode(' · ', $menu))?></div>
</div>