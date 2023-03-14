<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['users'];
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add($lng['users']);
$_breadcrumb = $breadcrumb->out();

$tpl_file = 'users::users';
$tpl_data['search_user_form_action'] = SITE_URL . '/users/search';
$tpl_data['count_users'] = counters::users();
$tpl_data['count_birth'] =  mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `dayb` = "' . date('j', SYSTEM_TIME) . '" AND `monthb` = "' . date('n', SYSTEM_TIME) . '" AND `preg` = "1"'), 0);
$tpl_data['count_admin'] =  mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `rights` > 0'), 0);
