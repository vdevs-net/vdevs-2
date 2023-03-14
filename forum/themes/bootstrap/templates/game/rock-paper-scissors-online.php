<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if (!$action): ?>
<div class="clearfix margin-bottom"><div class="pull-right"><a href="rock-paper-scissors-online?mod=top" class="btn btn-success btn-sm">Top cao thủ</a><a href="rock-paper-scissors-online?mod=create" class="btn btn-primary btn-sm margin-left">Đặt kèo <span class="badge"><b class="red"><?=$countRoom?></b> / <?=$maxRoom?></span></a></div></div>
<?php endif ?>
<div class="panel panel-default">
<?php if ($action == 'create'): ?>
<div class="panel-heading clearfix"><h4 class="panel-title">Đặt kèo</h4></div>
<div class="panel-body">
<?php if ($error): ?>
    <div class="alert alert-danger"><?=$error?></div>
<?php elseif ($success): ?>
    <div class="alert alert-info"><?=$success?></div>
<?php else: ?>
    <div class="alert alert-info"><?=$game_description?></div>
<?php endif ?>
    <form action="<?=$site_path?>/game/rock-paper-scissors-online?mod=create" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="coin" class="control-label col-sm-3">Số xu cược</label>
        <div class="col-sm-9">
            <input id="coin" name="coin" class="form-control" autocomplete="off" value="<?=$coinInput?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="control-label col-sm-3">Lựa chọn</div>
        <div class="col-sm-9">
            <div class="row text-center">
                <div class="col-xs-4"><div><input type="radio" name="select" value="1" id="s_1"<?php if ($select == 1): ?> checked="checked"<?php endif ?> /></div><div><label for="s_1"><img src="<?=$site_path?>/assets/images/ott/keo.png" max-width="100%" /></label></div></div>
                <div class="col-xs-4"><div><input type="radio" name="select" value="2" id="s_2"<?php if ($select == 2): ?> checked="checked"<?php endif ?> /></div><div><label for="s_2"><img src="<?=$site_path?>/assets/images/ott/bua.png" max-width="100%" /></label></div></div>
                <div class="col-xs-4"><div><input type="radio" name="select" value="3" id="s_3"<?php if ($select == 3): ?> checked="checked"<?php endif ?> /></div><div><label for="s_3"><img src="<?=$site_path?>/assets/images/ott/bao.png" max-width="100%" /></label></div></div>
            </div>
        </div>
    </div>
    <hr class="devider" />
    <div class="form-group"><div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="Đặt kèo" class="btn btn-primary" /></div></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
</div>
<?php if ($items): ?>
<div class="list-group">
<?php foreach ($items as $item): ?>
    <div class="list-group-item">Đã chọn <b><?=$item['choice']?></b>, mức cược <b><?=$item['coin']?></b> xu (<?=$item['time']?>)</div>
<?php endforeach ?>
</div>
<?php endif ?>
<?php elseif ($action == 'room'): ?>
<div class="panel-heading clearfix"><h4 class="panel-title">Bắt kèo</h4></div>
<div class="panel-body">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
            <td width="48"><img src="<?=$userAvatar?>" alt="<?=$userAccount?>" title="<?=$userAccount?>" /></td>
            <td class="padding-left">
                <div><a class="<?=$userClass?>" href="<?=$userProfileUrl?>"><?=$userAccount?></a> <small>(<?=$roomTime?>)</small></div>
                <div>Đặt cược: <b><?=$roomCoin?></b> xu</div>
            </td>
        </tr>
    </table>
    <hr class="devider" />
<?php if ($win): ?>
    <div class="alert alert-info">Chúc mừng bạn đã dành chiến thắng!</div>
<?php elseif ($tied): ?>
    <div class="alert alert-warning">Kết quả hoà! Chúc bạn may mắn lần sau.</div>
<?php elseif ($lose): ?>
    <div class="alert alert-danger">Rất tiếc bạn đã thua rồi!</div>
<?php else: ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif ?>
    <form action="<?=$formAction?>" method="post" class="form-horizontal">
    <div class="form-group">
            <div class="row text-center">
                <div class="col-xs-4"><div><input type="radio" name="select" value="1" id="s_1"<?php if ($select == 1): ?> checked="checked"<?php endif ?> /></div><div><label for="s_1"><img src="<?=$site_path?>/assets/images/ott/keo.png" max-width="100%" /></label></div></div>
                <div class="col-xs-4"><div><input type="radio" name="select" value="2" id="s_2"<?php if ($select == 2): ?> checked="checked"<?php endif ?> /></div><div><label for="s_2"><img src="<?=$site_path?>/assets/images/ott/bua.png" max-width="100%" /></label></div></div>
                <div class="col-xs-4"><div><input type="radio" name="select" value="3" id="s_3"<?php if ($select == 3): ?> checked="checked"<?php endif ?> /></div><div><label for="s_3"><img src="<?=$site_path?>/assets/images/ott/bao.png" max-width="100%" /></label></div></div>
            </div>
    </div>
    <hr class="devider" />
    <div class="form-group"><div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="Bắt kèo" class="btn btn-primary" /></div></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
    </form>
<?php endif ?>
</div>
<?php elseif ($action == 'top'): ?>
<div class="panel-heading clearfix"><h4 class="panel-title">Top cao thủ</h4></div>
    <?php if ($items): ?>
    <div class="list-group">
        <?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="48"><img src="<?=$item['userAvatar']?>" alt="<?=$item['userAccount']?>" title="<?=$item['userAccount']?>" /></td>
                    <td class="padding-left">
                        <div><a class="<?=$item['userHTMLClass']?>" href="<?=$item['userProfileUrl']?>"><?=$item['userAccount']?></a></div>
                        <div>Kèo thắng: <b><?=$item['winCount']?></b></div>
                        <div>Tỉ lệ thắng: <b><?=$item['winRate']?></b></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php endforeach ?>
    </div>
    <?php else: ?>
        <div class="panel-body"><?=$lang['list_empty']?></div>
    <?php endif ?>
<?php else: ?>

<div class="panel-heading clearfix"><h4 class="panel-title">Danh sách kèo</h4></div>
<?php if ($total): ?>
    <div class="list-group">
<?php foreach ($items as $item): ?>
        <div class="list-group-item">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr valign="top">
                    <td width="48"><img src="<?=$item['user_avatar']?>" alt="<?=$item['user_account']?>" title="<?=$item['user_account']?>" /></td>
                    <td class="padding-left">
                        <div><a class="<?=$item['user_class']?>" href="<?=$item['user_profile_url']?>"><?=$item['user_account']?></a> <small>(<?=$item['time']?>)</small></div>
                        <div>Đặt cược: <b><?=$item['coin']?></b> xu</div>
                        <div><a href="<?=$item['url']?>" class="btn btn-primary btn-xs">Bắt kèo</a></div>
                    </td>
                </tr>
            </table>
        </div>
<?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel-body"><?=$lang['list_empty']?></div>
<?php endif ?>

<?php endif ?>
</div>
<?php if (!$action && $pagination): ?>
    <div class="clearfix margin-top margin-bottom"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>
