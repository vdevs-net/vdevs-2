<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?=$lang_iso?>">
<head>
<?php foreach ($meta_tags as $meta): ?>
    <meta <?=$meta['name']?>="<?=$meta['value']?>" content="<?=$meta['content']?>" />
<?php endforeach ?>
    <title><?=$page_title?></title>
<?php foreach ($html_links as $link): ?>
    <link <?php foreach ($link as $key => $value): ?><?=$key?>="<?=$value?>" <?php endforeach ?>/>
<?php endforeach ?>
</head>
<body basesrc="<?=$site_path?>" id="<?=$headmod?>" ses="<?=$csrf_token?>">
    <div id="container">
    <?php if ($cms_ads[0]): ?>
        <div class="gmenu"><?=$cms_ads[0]?></div>
    <?php endif ?>
        <div id="header">
            <a id="top">&nbsp;</a>
            <div class="box">
            <?php if ($loged): ?>
                <div class="phdr"><a href="<?=$site_path?:'/' ?>"><b>Home</b></a> · <a href="<?=$user['profile_url']?>"><?=$lang['personal']?></a> · <a href="<?=$site_path?>/farm/"><b>Nông trại</b></a> · <a href="<?=$site_path?>/account/">Tài khoản</a> · <a href="<?=$site_path?>/shop/">Cửa hàng</a> · <a href="<?=$site_path?>/messages/"><?=$lang['mail']?></a><?php if ($rights >= 1): ?> · <a href="<?=$site_path?>/<?=$set['admp']?>/"><b><?=$lang['admin_panel']?></b></a><?php endif ?> · <a href="<?=$site_path?>/account/logout"><?=$lang['logout']?></a></div>
                <div class="menu"><table cellpadding="0" cellspacing="0" width="100%"><tr valign="top"><td width="38"><img src="<?=$user['avatar']?>" width="32" height="32" alt="<?=$user['account']?>" /></td><td><div><b><?=$user['account']?></b></div><div><img src="<?=$site_path?>/assets/images/coin.png"> <?=$user['coin']?> - <img src="<?=$site_path?>/assets/images/gold.png"/> <?=$user['gold']?></div></td><td align="right"><div><?=$user['rights']?></div></td></tr></table></div>
            <?php else: ?>
                <div class="phdr"><a href="<?=$site_path?:'/' ?>"><b>Home</b></a> · <a href="<?=$site_path?>/login/"><?=$lang['login']?></a> · <a href="<?=$site_path?>/misc/help" class="reg_link"><b>FAQ</b></a></div>
                <div class="list1"><form action="<?=$site_path?>/login/" method="post"><input type="text" name="account" maxlength="32" size="5" class="name" autocomplete="off"/><input type="password" name="password" maxlength="32" size="5" class="pass" autocomplete="off"/><input type="hidden" name="mem" value="1" /><input type="submit" name="submit" value="&#160;<?=$lang['login']?>&#160;"/></form></div>
                <div class="topmenu"><a href="<?=$site_path?>/register/"><b><font color="red"><?=$lang['registration']?></font></b></a> · <a href="<?=$site_path?>/login/facebook">Login with Facebook</a> · <a href="<?=$site_path?>/account/recover" title="<?=$lang['forgotten_password']?>"><?=$lang['forgotten_password']?></a></div>
            <?php endif ?>
            </div>
        </div>
        <div id="body" class="maintxt container">
        <?php if ($cms_ads[1]): ?>
            <div class="gmenu"><?=$cms_ads[1]?></div>
        <?php endif ?>
        <?php if ($loged): ?>
            <?php if ($ban): ?>
                <div class="alarm"><?=$lang['ban']?>&#160;<a href="<?=$user['profile_url']?>ban"><?=$lang['in_detail']?></a></div>
            <?php endif ?>
            <?php if ($unread_message || $unread_notification): ?>
                <div class="rmenu"><?=$lang['unread']?>: <?php if ($unread_notification): ?><a href="<?=$site_path?>/messages/systems"><?=$lang['system']?></a> (<?=$unread_notification?>)<?php endif ?><?php if ($unread_message && $unread_notification): ?>, <?php endif ?><?php if ($unread_message): ?><a href="<?=$site_path?>/messages/new"><?=$lang['mail']?></a> (<?=$unread_message?>)<?php endif ?></div>
            <?php endif ?>
        <?php endif ?>
            <?=$breadcrumb?>

            <?=$this->section('content')?>

        <?php if ($cms_ads[2]): ?>
            <div class="gmenu"><?=$cms_ads[2]?></div>
        <?php endif ?>
        </div><!--/ #body -->
        <div id="footer">
            <div class="phdr"><a href="<?=$site_path?>"><?=$lang['homepage']?></a></div>
            <div class="menu center">
                <div>Copyright &copy; 2023 - <?=$year?> <?=$set['copyright']?></div>
                <div>Design by: <a href="https://forum.vdevs.net" target="_blank">vDevs Forum</a></div>
            </div>
            <div class="menu center"><a href="<?=$site_path?>/misc/about"><?=$lang['about']?></a> | <a href="<?=$site_path?>/misc/terms"><?=$lang['terms']?></a></div>
            <?php if ($cms_ads[3]): ?>
                <div class="gmenu"><?=$cms_ads[3]?></div>
            <?php endif ?>
        </div><!--/ #footer -->
    </div><!--/ #container -->
    <?php foreach($html_js as $js): ?>
        <?php if ($js['ext'] == 1): ?>
            <script type="text/javascript" src="<?=$js['content']?>"></script>
        <?php else: ?>
            <script type="text/javascript"><?=$js['content']?></script>
        <?php endif ?>
    <?php endforeach ?>
</body>
</html>
