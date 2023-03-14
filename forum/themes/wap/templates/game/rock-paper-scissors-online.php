<?php
    $this->layout('layout');
?>
<?php if (!$action): ?>
<div class="topmenu"><a href="rock-paper-scissors-online?mod=create">Đặt kèo</a> [<span class="red"><b><?=$countRoom?></b></span> / <?=$maxRoom?>] | <a href="rock-paper-scissors-online?mod=top">Top cao thủ</a></div>
<?php endif ?>
<div class="panel panel-default">
<?php if ($action == 'create'): ?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php elseif ($success): ?>
    <div class="notif"><?=$success?></div>
<?php else: ?>
    <div class="notif"><?=$game_description?></div>
<?php endif ?>
    <form action="<?=$site_path?>/game/rock-paper-scissors-online?mod=create" method="post">
    <div class="menu">
        <h3>Số xu cược</h3>
        <input id="coin" name="coin" autocomplete="off" value="<?=$coinInput?>" />
    </div>
    <div class="menu">
        <h3>Lựa chọn</h3>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" width="33%"><div><input type="radio" name="select" value="1" id="s_1"<?php if ($select == 1): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/keo.png" max-width="100%" /></div></td>
                <td align="center" width="33%"><div><input type="radio" name="select" value="2" id="s_2"<?php if ($select == 2): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/bua.png" max-width="100%" /></div></td>
                <td align="center" width="33%"><div><input type="radio" name="select" value="3" id="s_3"<?php if ($select == 3): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/bao.png" max-width="100%" /></div></td>
            </tr>
        </table>
    </div>
    <div class="menu"><div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="Đặt kèo" class="btn btn-primary" /></div></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php if ($items): ?>
<?php foreach ($items as $item): ?>
    <div class="menu">Đã chọn <b><?=$item['choice']?></b>, mức cược <b><?=$item['coin']?></b> xu (<?=$item['time']?>)</div>
<?php endforeach ?>
<?php endif ?>
<?php elseif ($action == 'room'): ?>
<div class="menu">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
            <td width="48"><img src="<?=$userAvatar?>" alt="<?=$userAccount?>" title="<?=$userAccount?>" /></td>
            <td class="pl5">
                <div><a class="<?=$userClass?>" href="<?=$userProfileUrl?>"><?=$userAccount?></a> <small>(<?=$roomTime?>)</small></div>
                <div>Đặt cược: <b><?=$roomCoin?></b> xu</div>
            </td>
        </tr>
    </table>
</div>
<?php if ($win): ?>
    <div class="gmenu">Chúc mừng bạn đã dành chiến thắng!</div>
<?php elseif ($tied): ?>
    <div class="gmenu">Kết quả hoà! Chúc bạn may mắn lần sau.</div>
<?php elseif ($lose): ?>
    <div class="rmenu">Rất tiếc bạn đã thua rồi!</div>
<?php else: ?>
    <?php if($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <form action="<?=$formAction?>" method="post" class="form-horizontal">
    <div class="menu">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" width="33%"><div><input type="radio" name="select" value="1" id="s_1"<?php if ($select == 1): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/keo.png" max-width="100%" /></div></td>
                <td align="center" width="33%"><div><input type="radio" name="select" value="2" id="s_2"<?php if ($select == 2): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/bua.png" max-width="100%" /></div></td>
                <td align="center" width="33%"><div><input type="radio" name="select" value="3" id="s_3"<?php if ($select == 3): ?> checked="checked"<?php endif ?> /></div><div><img src="<?=$site_path?>/assets/images/ott/bao.png" max-width="100%" /></div></td>
            </tr>
        </table>
        </div>
    <div class="menu"><div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="Bắt kèo" class="btn btn-primary" /></div></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php endif ?>
<?php elseif ($action == 'top'): ?>
    <?php if ($items): ?>
        <?php foreach ($items as $item): ?>
        <div class="menu">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="48"><img src="<?=$item['userAvatar']?>" alt="<?=$item['userAccount']?>" title="<?=$item['userAccount']?>" /></td>
                    <td class="pl5">
                        <div><a class="<?=$item['userHTMLClass']?>" href="<?=$item['userProfileUrl']?>"><?=$item['userAccount']?></a></div>
                        <div>Kèo thắng: <b><?=$item['winCount']?></b></div>
                        <div>Tỉ lệ thắng: <b><?=$item['winRate']?></b></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php endforeach ?>
    <?php else: ?>
        <div class="rmenu"><?=$lang['list_empty']?></div>
    <?php endif ?>
<?php else: ?>

<?php if ($total): ?>
<?php foreach ($items as $item): ?>
    <div class="menu">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td width="48"><img src="<?=$item['user_avatar']?>" alt="<?=$item['user_account']?>" title="<?=$item['user_account']?>" /></td>
                <td class="pl5">
                    <div><a class="<?=$item['user_class']?>" href="<?=$item['user_profile_url']?>"><?=$item['user_account']?></a> <small>(<?=$item['time']?>)</small></div>
                    <div>Đặt cược: <b><?=$item['coin']?></b> xu</div>
                    <div><a href="<?=$item['url']?>" class="btn btn-primary btn-xs">Bắt kèo</a></div>
                </td>
            </tr>
        </table>
    </div>
<?php endforeach ?>
<?php if ($pagination): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>

<?php endif ?>
</div>
