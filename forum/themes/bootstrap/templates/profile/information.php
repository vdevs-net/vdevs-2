<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<div class="panel panel-primary"><div class="panel-body">
<div class="menu">
    <p>
        <h4><?=$lang['personal_data']?></h4>
        <ul>
            <li><span class="gray"><?=$lang['name']?>:</span> <?=$user_name?></li>
            <li><span class="gray">Giới tính:</span> <?=$user_sex?></li>
            <li><span class="gray"><?=$lang['birt']?>:</span> <?=$user_birthday?></li>
            <li><span class="gray"><?=$lang['city']?>:</span> <?=$user_address?></li>
            <li><span class="gray">Tiền:</span> <?=$user_coin?></li>
            <li><span class="gray">Vàng:</span> <?=$user_gold?></li>
            <li><div><span class="gray"><?=$lang['about']?>:</span></div><div><?=$user_about?></div></li>
        </ul>
    </p>
    <p>
        <h4><?=$lang['communication']?></h4>
        <ul>
        <?php if ($show_contact): ?>
            <?php if ($user_mobile): ?><li><span class="gray"><?=$lang['phone_number']?>:</span> <?=$user_mobile?><?php if ($hide_contact_set): ?><span class="gray"> [<?=$lang['hidden']?>]</span><?php endif ?></li><?php endif ?>
            <?php if ($user_email): ?><li><span class="gray">E-mail:</span> <?=$user_email?><?php if ($hide_contact_set): ?> <span class="gray">[<?=$lang['hidden']?>]</span><?php endif ?></li><?php endif ?>
        <?php endif ?>
            <li><span class="gray">Facebook:</span> <?=$user_facebook?></li>
        </ul>
    </p>
</div>

<div class="menu">
    <p>
        <h4><?=$lang['statistics']?></h4>
        <ul>
            <?php if ($user_register_status): ?>
                <li><?=$user_register_status?></li>
            <?php endif ?>
            <li><span class="gray"><?=$lang['registered']?>:</span> <?=$user_register_date?></li>
            <li><span class="gray"><?=$lang['stayed']?>:</span> <?=$user_online_time?></li>
            <?php if ($user_last_visit): ?>
                <li><span class="gray"><?=$lang['last_visit']?>:</span> <?=$user_last_visit?></li>
            <?php endif ?>
            <?php if ($user_ban_count): ?>
                <li><a href="<?=$user_ban_url?>"><?=$lang['infringements']?></a> (<?=$user_ban_count?>)</li>
            <?php endif ?>
        </ul>
    </p>
    <p>
        <h4><?=$lang['activity']?></h4>
        <ul>
            <li><span class="gray"><?=$lang['forum']?>:</span> <a href="<?=$user_post_forum_url?>"><?=$user_post_forum?></a></li>
            <li><span class="gray"><?=$lang['comments']?>:</span> <?=$user_comment?></li>
        </ul>
    </p>
    <p>
        <h4><?=$lang['achievements']?></h4>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
            <?php foreach ($points as $point): ?>
                <td width="28" align="center"><small><?=$point?></small></td>
            <?php endforeach ?>
                <td></td>
            </tr>
            <?php foreach ($fields as $key => $val): ?>
                <tr>
                <?php foreach ($points as $achieve): ?>
                    <td align="center"><img src="<?=$site_path?>/assets/images/<?php if ($user_fields[$key] >= $achieve): ?>green<?php else: ?>red<?php endif ?>.gif" /></td>
                <?php endforeach ?>
                <td><small><b><?=$val?></b></small></td></tr>
            <?php endforeach?>
        </table>
    </p>
</div>
</div></div>
