<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['administration']);
$_breadcrumb = $breadcrumb->out();

$tpl_file = 'admin::usr-adm';
$tpl_data['total'] = 0;
$tpl_data['super_admin'] = $tpl_data['admin'] = $tpl_data['super_moder'] = $tpl_data['moder'] = [];

$req = mysql_query('SELECT * FROM `users` WHERE `rights` = "9" ORDER BY `account` ASC');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        $tpl_data['super_admin'][] = functions::display_user($res, array('header' => ('<b>ID:' . $res['id'] . '</b>')));
        ++$tpl_data['total'];
    }
}

$req = mysql_query('SELECT * FROM `users` WHERE `rights` = "7" ORDER BY `account` ASC');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        $tpl_data['admin'][] =  functions::display_user($res, array('header' => ('<b>ID:' . $res['id'] . '</b>')));
        ++$tpl_data['total'];
    }
}
$req = mysql_query('SELECT * FROM `users` WHERE `rights` = "6" ORDER BY `account` ASC');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        $tpl_data['super_moder'][] = functions::display_user($res, array('header' => ('<b>ID:' . $res['id'] . '</b>')));
        ++$tpl_data['total'];
    }
}
$req = mysql_query('SELECT * FROM `users` WHERE `rights` BETWEEN "1" AND "5" ORDER BY `account` ASC');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        $tpl_data['moder'][] =  functions::display_user($res, array('header' => ('<b>ID:' . $res['id'] . '</b>')));
        ++$tpl_data['total'];
    }
}
