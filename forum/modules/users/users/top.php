<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

function get_top($order = 'postforum') {
    $return = [];
    $req = mysql_query('SELECT * FROM `users` WHERE `' . $order . '` > "0" ORDER BY `' . $order . '` DESC LIMIT 10');

    while ($res = mysql_fetch_assoc($req)) {
        $return[] = [
            'content' => functions::display_user($res, array ('header' => '<b>' . $res[$order] . '</b>', 'iphide' => 1, 'stshide' => 1))
        ];
    }
    return $return;
}

$headmod = 'userstop';
$page_title = $lng['users_top'];
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

require(ROOTPATH . 'system/header.php');
$tpl_file = 'users::top';
$tpl_data['tabs'] = [
    [
        'url' => 'top',
        'name' => $lng['forum'],
        'active' => ($type != 'comment' && $type != 'coin')
    ],
    [
        'url' => 'top?type=comment',
        'name' => $lng['comments'],
        'active' => ($type == 'comment')
    ],
    [
        'url' => 'top?type=coin',
        'name' => 'Xu',
        'active' => ($type == 'coin')
    ]

];


$breadcrumb = new breadcrumb();
$breadcrumb->add('/users/', $lng['users']);

switch ($type) {
	case 'coin':
        $breadcrumb->add('Top xu');
        $type = 'coin';
        break;
    case 'comment':
        $breadcrumb->add($lng['top_comm']);
        $type = 'komm';
        break;

    default:
        $breadcrumb->add($lng['top_forum']);
        $type = 'postforum';
}
$_breadcrumb = $breadcrumb->out();

$tpl_data['items'] = get_top($type);
