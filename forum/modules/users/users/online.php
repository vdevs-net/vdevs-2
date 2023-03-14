<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'online';
$page_title = $lng['online'];
require(ROOTPATH . 'system/header.php');
$tpl_file = 'users::online';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/users/', $lng['community']);
$breadcrumb->add($lng['who_on_site']);
$_breadcrumb = $breadcrumb->out();

// Displays a list of Online
$tpl_data['tabs'] = array(
    array(
        'url' => 'online',
        'name' => $lng['users'],
        'active' => (($rights && $mod != 'guest' && $mod != 'ip' && $mod != 'history') || (!$rights && $mod != 'history'))
    ),
    array(
        'url' => 'online?mod=history',
        'name' => $lng['history'],
        'active' => ($mod == 'history')
    )
);
if ($rights) {
    $tpl_data['tabs'][] = array(
        'url' => 'online?mod=guest',
        'name' => $lng['guests'],
        'active' => ($mod == 'guest')
    );
    $tpl_data['tabs'][] = array(
        'url' => 'online?mod=ip',
        'name' => $lng['ip_activity'],
        'active' => ($mod == 'ip')
    );
}
$tpl_data['items'] = [];
if ($mod == 'ip' && $rights) {
        // Список активных IP, со счетчиком обращений
        $ip_array = array_count_values(core::$ip_count);
        $total = count($ip_array);
        $tpl_data['total'] = $total;
        $start = functions::fixStart($start, $total, $kmess);
        $end = $start + $kmess;
        if ($end > $total) {
            $end = $total;
        }
        arsort($ip_array);
        $i = 0;
        foreach ($ip_array as $key => $val) {
            $ip_list[$i] = array($key => $val);
            ++$i;
        }
        if ($total) {
            for ($i = $start; $i < $end; $i++) {
                $out = each($ip_list[$i]);
                $ip = long2ip($out[0]);
                $tpl_data['items'][] = [
                    'html_class' => ($out[0] == core::$ip ? 'gmenu' : 'menu'),
                    'content'    => '[' . $out[1] . '] <a href="' . SITE_URL . '/' . $set['admp'] . '/search-ip?ip=' . $ip . '">' . $ip . '</a> <small>[<a href="' . SITE_URL . '/' . $set['admp'] . '/ip-whois?ip=' . $ip . '">?</a>]</small>'
                ];
            }
        }
} else {
    switch ($mod) {
        case 'history':
            $sql_total = 'SELECT COUNT(*) FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 172800) . ' AND `lastdate` < ' . (SYSTEM_TIME - 310);
            $sql_list = 'SELECT * FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 172800) . ' AND `lastdate` < ' . (SYSTEM_TIME - 310) . ' ORDER BY `sestime` DESC';
            break;

        case 'guest':
            if ($rights) {
                $sql_total = 'SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > ' . (SYSTEM_TIME - 300);
                $sql_list = 'SELECT * FROM `cms_sessions` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' ORDER BY `movings` DESC';
                break;
            }

        default:
            $sql_total = 'SELECT COUNT(*) FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 300);
            $sql_list = 'SELECT * FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' ORDER BY `account` ASC';
            $mod = '';
    }

    $total = mysql_result(mysql_query($sql_total), 0);
    $tpl_data['total'] = $total;
    $start = functions::fixStart($start, $total, $kmess);
    if ($total) {
        $req = mysql_query($sql_list . ' LIMIT ' . $start . ', ' . $kmess);
        while ($res = mysql_fetch_assoc($req)) {
    		if ($mod == 'guest') {
                $res['id'] = 0;
            }
            $arg['stshide'] = 1;
            $arg['header'] = ' <span class="gray">(';
            if ($mod == 'history') {
                $arg['header'] .= functions::display_date($res['sestime']);
            } else {
                $arg['header'] .= $res['movings'] . ' - ' . functions::timecount(SYSTEM_TIME - $res['sestime']);
            }
            $arg['header'] .= ')</span> - ' . functions::display_place($res['id'], $res['place']);

            $tpl_data['items'][] = [
                'html_class' => ($res['id'] == $user_id ? 'gmenu' : 'menu'),
                'content' => functions::display_user($res, $arg)
            ];
        }
    }
}
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('online?' . ($mod ? 'mod=' . $mod . '&' : '') . 'page=', $start, $total, $kmess) : '');
