<?php
    $this->layout('layout');
?>
<div class="user"><p><h3><?=$lang['users']?></h3><ul>
<?php if ($reg_count && $rights >= RIGHTS_SUPER_ADMIN): ?>
    <li><span class="red"><b><a href="reg"><?=$lang['users_reg']?></a> (<?=$reg_count?>)</b></span></li>
<?php endif ?>
    <li><a href="usr"><?=$lang['users']?></a> (<?=$usr_count?>)</li>
    <li><a href="usr-adm"><?=$lang['users_administration']?></a> (<?=$adm_count?>)</li>
<?php if ($rights >= RIGHTS_ADMIN): ?>
    <li><a href="usr-clean"><?=$lang['users_clean']?></a></li>
<?php endif ?>
    <li><a href="ban-panel"><?=$lang['ban_panel']?></a> (<?=$ban_count?>)</li>
<?php if ($rights >= RIGHTS_ADMIN): ?>
    <li><a href="antiflood"><?=$lang['antiflood']?></a></li>
<?php endif ?>
    <br/>
    <li><a href="<?=SITE_URL?>/users/search"><?=$lang['search_nick']?></a></li>
    <li><a href="search-ip"><?=$lang['ip_search']?></a></li>
    </ul></p></div>
<?php if ($rights >= RIGHTS_ADMIN): ?>
<div class="gmenu"><p><h3><?=$lang['modules']?></h3><ul>
    <li><a href="forum"><?=$lang['forum']?></a></li>
    <li><a href="news"><?=$lang['news']?></a></li>
    <li><a href="ads"><?=$lang['advertisement']?></a></li>
    <?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
        <br/><li><a href="shop">Shop</a></li>
        <li><a href="mail"><?=$lang['mail']?></a></li>
        <li><a href="stats">Stats</a></li>
    <?php endif ?>
    </ul></p></div>
    <div class="menu"><p><h3><?=$lang['system']?></h3><ul>
    <?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
        <li><a href="settings"><b><?=$lang['site_settings']?></b></a></li>
        <li><a href="languages"><?=$lang['language_settings']?></a></li>
    <?php endif ?>
        <li><a href="access"><?=$lang['access_rights']?></a></li>
    </ul></p></div>
    <div class="rmenu"><p><h3><?=$lang['security']?></h3><ul>
        <li><a href="antispy"><?=$lang['antispy']?></a></li>
        <?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
            <li><a href="ipban"><?=$lang['ip_ban']?></a></li>
        <?php endif ?>
    </ul></p></div>
<?php endif ?>
<div class="phdr" style="font-size: x-small"><b>JohnCMS 6.2.1</b></div>