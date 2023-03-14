<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'userban';
$lng = array_merge($lng, core::load_lng('ban'));
require(ROOTPATH . 'system/header.php');
$tpl_file = 'profile::ban';

$total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_ban_users` WHERE `user_id` = '" . $user['id'] . "'"), 0);
$tpl_data['total'] = $total;
$tpl_data['items'] = [];
if ($total) {
    $req = mysql_query("SELECT * FROM `cms_ban_users` WHERE `user_id` = '" . $user['id'] . "' ORDER BY `ban_time` DESC LIMIT $start, $kmess");
    while ($res = mysql_fetch_assoc($req)) {
        $remain = $res['ban_time'] - SYSTEM_TIME;
        $period = $res['ban_time'] - $res['ban_while'];
        // todo: moder link
        $menu = array();
        if ($rights >= 7 && $remain > 0) {
            $menu[] = [
                'url' => SITE_URL . '/' . $set['admp'] . '/usr?mod=ban&do=cancel&id=' . $user['id'] . '&ban=' . $res['id'],
                'name' => $lng['ban_cancel_do']
            ];
        }
        if ($rights == 9) {
            $menu[] = [
                'url' => SITE_URL . '/' . $set['admp'] . '/usr?mod=ban&do=delete&id=' . $user['id'] . '&ban=' . $res['id'],
                'name' => $lng['ban_delete_do']
            ];
        }
        $tpl_data['items'][] = [
            'remain'  => $remain,
            'remain_time' => functions::timecount($remain),
            'term'    => ($res['ban_time'] - $res['ban_while'] < 86400000 ? functions::timecount($period) : $lng['ban_time_before_cancel']),
            'type'    => isset($lng['ban_' . $res['ban_type']]) ? $lng['ban_' . $res['ban_type']] : 'Unknow',
            'time'    => functions::display_date($res['ban_while']),
            'reason'  => functions::checkout($res['ban_reason']),
            'ban_who' => ($rights > 0 ? $res['ban_who'] : ''),
            'menu'    => $menu
        ];
    }
}
$tpl_data['delete_history_url'] = ($rights == 9 ? SITE_URL . '/' . $set['admp'] . '/usr?mod=ban&do=delhist&id=' . $user['id'] : '');
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('ban?page=', $start, $total, $kmess) : '');
