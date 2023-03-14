<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['ip_search']);
$_breadcrumb = $breadcrumb->out();

$search = (isset($_GET['ip'])) ? rawurldecode(trim($_GET['ip'])) : false;
$tpl_file = 'admin::search-ip';
$tpl_data['tabs'] = [
    [
        'url' => 'search-ip'. ($search ? '?ip=' . rawurlencode($search) : ''),
        'name' => $lng['ip_actual'],
        'active' => $mod != 'history'
    ],
    [
        'url' => 'search-ip?mod=history' . ($search ? '&ip=' . rawurlencode($search) : ''),
        'name' => $lng['ip_history'],
        'active' => $mod == 'history'
    ]
];
switch ($mod) {
    case 'history':
        $tpl_data['hiddenInput'] = '<input type="hidden" name="mod" value="history" />';
        break;
    
    default:
        $tpl_data['hiddenInput'] = '';
        break;
}
$tpl_data['search_keyword'] = functions::checkout($search);
$error = array();
if ($search) {
    if (strstr($search, '-')) {
        // Обрабатываем диапазон адресов
        $array = explode('-', $search);
        $ip = trim($array[0]);
        if (core::ip_valid($ip)) {
            $ip1 = ip2long($ip);
        } else {
            $error[] = $lng['error_firstip'];
        }
        $ip = trim($array[1]);
        if (core::ip_valid($ip)) {
            $ip2 = ip2long($ip);
        } else {
            $error[] = $lng['error_secondip'];
        }
    } elseif (strstr($search, '*')) {
        // Обрабатываем адреса с маской
        $array = explode('.', $search);
        for ($i = 0; $i < 4; $i++) {
            if (!isset($array[$i]) || $array[$i] == '*') {
                $ipt1[$i] = '0';
                $ipt2[$i] = '255';
            } elseif (is_numeric($array[$i]) && $array[$i] >= 0 && $array[$i] <= 255) {
                $ipt1[$i] = $array[$i];
                $ipt2[$i] = $array[$i];
            } else {
                $error = $lng['error_address'];
            }
        }
        if (!$error) {
            $ip1 = ip2long($ipt1[0] . '.' . $ipt1[1] . '.' . $ipt1[2] . '.' . $ipt1[3]);
            $ip2 = ip2long($ipt2[0] . '.' . $ipt2[1] . '.' . $ipt2[2] . '.' . $ipt2[3]);
        }
    } else {
        // Обрабатываем одиночный адрес
        if (core::ip_valid($search)) {
            $ip1 = ip2long($search);
            $ip2 = $ip1;
        } else {
            $error = $lng['error_address'];
        }
    }
}
$tpl_data['error'] = ($error ? functions::display_error($error) : '');
$tpl_data['show_result'] = false;
$tpl_data['pagination'] = '';
$tpl_data['items'] = [];
if ($search && !$error) {
    $tpl_data['show_result'] = true;
    // Выводим результаты поиска
    $ip1 = sprintf('%u', $ip1);
    $ip2 = sprintf('%u', $ip2);
    if ($mod == 'history') {
        $total = mysql_result(mysql_query("SELECT COUNT(DISTINCT `user_id`) FROM `cms_users_iphistory` WHERE `ip` BETWEEN $ip1 AND $ip2 OR `ip_via_proxy` BETWEEN $ip1 AND $ip2"), 0);
    } else {
        $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `ip` BETWEEN $ip1 AND $ip2 OR `ip_via_proxy` BETWEEN $ip1 AND $ip2"), 0);
    }
    if ($total > $kmess) {
        $tpl_data['pagination'] = functions::display_pagination('search-ip?' . ($mod == 'history' ? 'mod=history&' : '') . 'ip=' . urlencode($search) . '&page=', $start, $total, $kmess);
    }
    $tpl_data['total'] = $total;
    if ($total) {
        if ($mod == 'history') {
            $req = mysql_query("SELECT `user_id`, MAX(`id`) as `id` FROM `cms_users_iphistory` WHERE `cms_users_iphistory`.`ip` BETWEEN $ip1 AND $ip2 OR `cms_users_iphistory`.`ip_via_proxy` BETWEEN $ip1 AND $ip2 GROUP BY `user_id` LIMIT $start, $kmess");
        } else {
            $req = mysql_query("SELECT * FROM `users`
            WHERE `ip` BETWEEN $ip1 AND $ip2 OR `ip_via_proxy` BETWEEN $ip1 AND $ip2
            ORDER BY `ip` ASC, `account` ASC LIMIT $start, $kmess");
        }
        while ($res = mysql_fetch_assoc($req)) {
            if ($mod == 'history') {
                $res = mysql_fetch_assoc(mysql_query('SELECT `cms_users_iphistory`.`ip`, `cms_users_iphistory`.`ip_via_proxy`, `cms_users_iphistory`.`user_id` as `id`, `users`.`account`, `users`.`rights`, `users`.`lastdate`, `users`.`sex`, `users`.`status`, `users`.`datereg`, `users`.`browser` FROM `cms_users_iphistory` LEFT JOIN `users` ON `cms_users_iphistory`.`user_id` = `users`.`id` WHERE `cms_users_iphistory`.`id` = "' . $res['id'] . '" LIMIT 1'));
            }
            $tpl_data['items'][] = [
                'content' => functions::display_user($res, array ('iphist' => 1))
            ];
        }
    }
}
