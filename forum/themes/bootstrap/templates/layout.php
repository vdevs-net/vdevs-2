<!DOCTYPE html>
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
<body id="<?=$headmod?>" ses="<?=$csrf_token?>">
    <div id="container">
    <?php if ($cms_ads[0]): ?>
        <div class="gmenu"><?=$cms_ads[0]?></div>
    <?php endif ?>
        <nav class="navbar navbar-default navbar-fixed-top" id="header">
            <div class="container">
                <div class="navbar-header">
                    <a role="button" class="noPusher navbar-toggle" id="toggleMenu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="navbar-brand" href="<?=$site_path ?: '/' ?>">vDevs</a>
                    <ul class="nav navbar-nav navbar-right" id="headerNav">
                    <?php if ($loged): ?>
                        <li class="has-badge"><a href="<?=$site_path?>/messages/systems" class="_notifications"  data-unread="<?=$unread_notification?>"><i class="fa fa-bell-o fa-lg"></i><?php if ($unread_notification): ?><span class="badge"><?=$this->fixBadge($unread_notification)?></span><?php endif ?></a></li>
                        <li class="has-badge"><a href="<?=$site_path?>/messages/<?php if ($unread_message): ?>new<?php endif ?>" class="_messages" data-unread="<?=$unread_message?>"><i class="fa fa-envelope-o fa-lg"></i><?php if ($unread_message): ?><span class="badge"><?=$this->fixBadge($unread_message)?></span><?php endif ?></a></li>
                    <?php else: ?>
                        <li><a href="<?=$site_path?>/account/register"><i class="fa fa-user-plus fa-lg"></i></a></li>
                        <li><a href="<?=$site_path?>/login/"><i class="fa fa-sign-in fa-lg"></i></a></li>
                    <?php endif ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="body" class="maintxt container">
            <div id="breadcrumb"><?=$breadcrumb?></div>
            <div class="row">
                <div class="col-md-8 col-lg-9" id="mainCol">
                <?php if ($cms_ads[1]): ?>
                    <div class="gmenu"><?=$cms_ads[1]?></div>
                <?php endif ?>
                <?php if ($ban): ?>
                    <div class="alert alert-danger"><?=$lang['ban']?> <a href="<?=$user['profile_url']?>ban"><?=$lang['in_detail']?></a></div>
                <?php endif ?>
                    <div id="mainContent">
                        <?=$this->section('content')?>
                    </div>
                <?php if ($cms_ads[2]): ?>
                    <div class="gmenu"><?=$cms_ads[2]?></div>
                <?php endif ?>
                </div>
                <div class="col-md-4 col-lg-3" id="subCol">
                    <?php if ($loged): ?>
                    <div class="panel panel-default">
                        <div class="miniProfileCard radiusTop">
                            <div class="miniProfileCover radiusTop" style="background-image:url('<?=$user['cover']?>')"></div>
                            <div class="miniProfileAvatar" style="background-image: url('<?=$user['avatar']?>')"></div>
                            <div class="miniProfileInfo">
                                <div class="miniProfileAccount"><a href="<?=$user['profile_url']?>"><b><?=$user['account']?></b></a></div>
                                <div class="miniProfileName"><?=$user['name']?></div>
                            </div>
                        </div>
                        <div class="miniProfileBalance row text-center">
                            <div class="col col-xs-6"><img src="<?=$site_path?>/assets/images/coin.png" /><br /><span class="userCoin"><?=$user['coin']?></span></div>
                            <div class="col col-xs-6"><img src="<?=$site_path?>/assets/images/gold.png" /><br /><span class="userGold"><?=$user['gold']?></span></div>
                        </div>
                        <div class="list-group">
                            <a class="list-group-item" href="<?=$site_path?>/account/">Tài khoản</a>
                        <?php if ($rights): ?>
                            <a class="list-group-item" href="<?=$site_path?>/<?=$set['admp']?>/"><?=$lang['admin_panel']?></a>
                        <?php endif ?>
                            <a class="list-group-item" href="<?=$site_path?>/account/logout"><?=$lang['logout']?></a>
                        </div>
                    </div>
                    <?php endif ?>
<?php if (!$loged): ?>
                    <div class="panel panel-primary subLoginForm">
                        <div class="panel-body">
                            <form action="<?=$site_path?>/login/" method="post" class="noPusher">
                                <div class="form-group">
                                    <label for="_account" class="control-label"><?=$lang['login_name']?></label>
                                    <input type="text" name="account" maxlength="30" class="form-control" id="_account" />
                                </div>
                                <div class="form-group">
                                    <label for="_password" class="control-label"><?=$lang['password']?></label>
                                    <input type="password" name="password" maxlength="32" class="form-control" id="_password" />
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="<?=$lang['login']?>" class="btn btn-primary btn-block" />
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <div class="checkbox" style="margin-top: 0;margin-bottom: 0"><label><input type="checkbox" name="mem" value="1" checked="checked"/><?=$lang['remember']?></label></div>
                                    </div>
                                    <div class="col-xs-6"><a href="<?=$site_path?>/account/recover" title="<?=$lang['forgotten_password']?>"><?=$lang['forgotten_password']?></a></div>
                                </div>
                                <div class="form-group">
                                    <a href="<?=$site_path?>/login/facebook" class="btn btn-social btn-facebook btn-block noPusher"><span class="fa fa-facebook"></span> Login with Facebook</a>
                                </div>
                            </form>
                        </div>
                    </div>
<?php endif ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">MENU</div>
                        <div class="list-group">
                            <a class="list-group-item" href="<?=$site_path?>/forum/"><?=$lang['forum']?></a>
                        <?php if ($loged): ?>
                            <a class="list-group-item _chats" href="<?=$site_path?>/chat/"><span>Chatbox</span></a>
                            <a class="list-group-item" href="<?=$site_path?>/farm/">Nông trại</a>
                        <?php endif ?>
                        <?php if ($loged): ?>
                            <a class="list-group-item" href="<?=$site_path?>/shop/">Shop</a>
                            <a class="list-group-item" href="<?=$site_path?>/game/">Game</a>
                        <?php endif ?>
                            <a class="list-group-item" href="<?=$site_path?>/tools/">Công cụ</a>
                            <a class="list-group-item" href="<?=$site_path?>/misc/help"><?=$lang['information']?></a>
                        <?php if ($show_users_link): ?>
                            <a class="list-group-item" href="<?=$site_path?>/users/"><?=$lang['users']?></a>
                        <?php endif ?>
                        </div>
                    </div>
                    <?php if ($cms_ads[3]): ?>
                        <div class="panel panel-default"><div class="panel-body"><?=$cms_ads[3]?></div></div>
                    <?php endif ?>
                </div>
            </div>
            <footer class="panel panel-default" id="footer">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p>Copyright &copy; 2023 - <?=$year?> <?=$set['copyright']?></p>
                            <p>Design by: <a href="https://forum.vdevs.net" target="_blank">vDevs Forum</a></p>
                        </div>
                        <div class="col-sm-6 footer-right">
                            <p><a href="<?=$site_path?>/misc/about"><?=$lang['about']?></a> | <a href="<?=$site_path?>/misc/terms"><?=$lang['terms']?></a></p>
                        </div>
                    </div>
                </div>
            </footer>
        </div><!--/ #body -->
<?php if ($loged): ?>
        <div class="chatboxFixed panel panel-primary in-panel hidden-xs hidden-sm" id="chatboxFixed">
            <div class="panel-heading" id="chatboxHeader"><span class="fa fa-comment"></span> Chatbox (<span class="_chat"><?=$chat_count?></span>)</div>
            <div id="chatboxBody">
                <div class="list-group list-group-sm nano" id="chatMessages">
                    <div class="nano-content" id="chatMessagesInner">
                    </div>
                </div>
                <div id="chatInput">
                    <form action="<?=$site_path?>/chat/ajax/send" method="post" id="quickChatForm" class="noPusher">
                    <div class="input-group input-group-sm">
                        <input id="quickChatInput" type="text" name="text" class="form-control" placeholder="Type your message here..." autocomplete="off" />
                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-warning btn-sm" id="quickChatSubmit" value="Send" />
                        </span>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
                    </form>
                </div>
            </div>
        </div>
<?php endif ?>
        <p id="back2top"><a href="#"><span></span></a></p>
    </div><!--/ #container -->
    <div class="menu-overlay" id="menuOverlay"></div>
    <a class="tmpLink hidden" id="tmpLink" href="#"></a>
    <script id="chatTemplate" type="text/x-tmpl"><div class="list-group-item" data-time="{%#o.data_time%}" data-id="{%#o.data_id%}">{% if (o.data_id) { %}<a href="#" class="confirm noPusher"><i class="fa fa-trash fa-fw"></i></a> {% } %}<a href="{%#o.user_profile_url%}" title="{%#o.time%}" class="{%#o.user_html_class%} chatUser noPusher">{%#o.user_name%}</a>: {%#o.text%}</div></script>
    <script id="forumTemplate" type="text/x-tmpl">
        <div class="list-group-item {%#o.class%}">{% if (o.icons) { %}{% var icon; for (icon in o.icons) { %}<img src="<?=$site_path?>/assets/images/{%#o.icons[icon]%}.gif" class="icon" alt="[*]" />{% } %}{% } %}{% if (o.prefix) { %}<span class="label label-{%#o.prefix%}">{%#o.prefix_name%}</span>{% } %}<a href="{%#o.url%}">{%#o.name%}</a> (<span class="red">{%#o.post_count%}</span>) [<a href="{%#o.last_user_url%}">{%#o.last_user_name%}</a>]</div>
    </script>
<?php foreach($html_js as $js): ?>
<?php if ($js['ext'] == 1): ?>
        <script type="text/javascript" src="<?=$js['content']?>"></script>
<?php else: ?>
        <script type="text/javascript"><?=$js['content']?></script>
<?php endif ?>
<?php endforeach ?>
    <?php if (!DEV_MODE): ?><iframe src="<?=$theme_path?>/vdevs.cache.php" style="display:none!important"></iframe><?php endif ?>
</body>
</html>
