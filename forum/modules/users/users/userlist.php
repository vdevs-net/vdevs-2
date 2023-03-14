<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
$tpl_file = 'users::list';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/users/', $lng['users']);
switch ($type) {
    case 'staff':
        $page_title = $lng['administration'];
        $headmod = 'admlist';
        $breadcrumb->add($lng['administration']);
        $users_sql_total = '`rights` >= "1"';
        $users_sql = '`rights` >= "1" ORDER BY `rights` DESC';
        $tpl_data['hidden_input'] = '<input type="hidden" name="type" value="staff" />';
        break;

    case 'birthday':
        $page_title = $lng['birthday_men'];
        $headmod = 'birth';
        $breadcrumb->add($lng['birthday_men']);
        $users_sql_total = '`dayb` = "' . date('j', SYSTEM_TIME) . '" AND `monthb` = "' . date('n', SYSTEM_TIME) . '" AND `preg` = "1"';
        $users_sql = '`dayb` = "' . date('j', SYSTEM_TIME) . '" AND `monthb` = "' . date('n', SYSTEM_TIME) . '" AND `preg` = "1"';
        $tpl_data['hidden_input'] = '<input type="hidden" name="type" value="birthday" />';
        break;
    
    default:
        $page_title = $lng['users_list'];
        $headmod = 'userlist';
        $breadcrumb->add($lng['users_list']);
        $users_sql_total = '`preg` = "1"';
        $users_sql = '`preg` = "1" ORDER BY `datereg` DESC';
        $tpl_data['hidden_input'] = '';
        $type = '';
}
$_breadcrumb = $breadcrumb->out();
$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE ' . $users_sql_total), 0);
$start = functions::fixStart($start, $total, $kmess);
$page = ceil(($start + 1) / $kmess);
$tpl_data['current_page'] = $page;
require(ROOTPATH . 'system/header.php');
$tpl_data['total'] = $total;
$tpl_data['items'] = [];
if ($total) {
    $req = mysql_query('SELECT `id`, `account`, `sex`, `lastdate`, `datereg`, `status`, `rights`, `ip`, `browser`, `rights` FROM `users` WHERE ' . $users_sql . ' LIMIT ' . $start . ', ' . $kmess);
    while ($res = mysql_fetch_assoc($req)) {
        $tpl_data['items'][] = [
            'content' => functions::display_user($res)
        ];
    }
}
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('userlist?' . ($type ? 'type=' . $type . '&' : '') . 'page=', $start, $total, $kmess) : '');