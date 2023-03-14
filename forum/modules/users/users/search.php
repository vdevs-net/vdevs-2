<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$search = isset($_GET['q']) ? rawurldecode(trim($_GET['q'])) : '';

if (isset($_GET['q']) && empty($search)) {
    header('Location: search'); exit;
}

$headmod = 'usersearch';
$page_title = $lng['search_user'];
require(ROOTPATH . 'system/header.php');

$tpl_file = 'users::search';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/users/', $lng['users']);
$breadcrumb->add($lng['search']);
$_breadcrumb = $breadcrumb->out();

$tpl_data['search'] = functions::checkout($search);

$error = array();
if (!empty($search) && (mb_strlen($search) < 3 || mb_strlen($search) > 30)) {
    $error[] = $lng['error_wrong_lenght'];
}
if (preg_match('/[^A-Za-z0-9\.]/', mb_strtolower($search))) {
    $error[] = $lng['error_wrong_symbols'];
}
$tpl_data['pagination'] = '';
$tpl_data['show_results'] = false;
if ($search && !$error) {
    $tpl_data['show_results'] = true;
    $search_db = mb_strtolower($search);
    $search_db = strtr($search_db, array (
        '_' => '\\_',
        '%' => '\\%'
    ));
    $search_db = '%' . $search_db . '%';
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `account` LIKE "' . mysql_real_escape_string($search_db) . '"'), 0);
    $tpl_data['total'] = $total;
    $tpl_data['items'] = [];
    if ($total) {
        $req = mysql_query('SELECT * FROM `users` WHERE `account` LIKE "' . mysql_real_escape_string($search_db) . '" ORDER BY `account` ASC LIMIT ' . $start . ', ' . $kmess);
        while ($res = mysql_fetch_assoc($req)) {
            $tpl_data['items'][] = [
                'content' => functions::display_user($res)
            ];
        }
    }
    $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('search?q=' . urlencode($search) . '&page=', $start, $total, $kmess) : '');
} else {
    $tpl_data['error'] = ($error ? functions::display_error($error): '');
}
