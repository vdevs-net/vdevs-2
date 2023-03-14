<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add($lng['admin_panel']);
$_breadcrumb = $breadcrumb->out();

$tpl_file = 'admin::main';
$tpl_data['reg_count'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `preg`="0"'), 0);
$tpl_data['usr_count'] = counters::users();
$tpl_data['adm_count'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `rights` >= "1"'), 0);
$tpl_data['ban_count'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_ban_users` WHERE `ban_time` > "' . SYSTEM_TIME . '"'), 0);
