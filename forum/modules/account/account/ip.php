<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $page_title = $lng['ip_history'];
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/account/', 'Tài khoản');
    $breadcrumb->add($lng['ip_history']);
    $_breadcrumb = $breadcrumb->out();
    // History of IP addresses
    $tpl_file = 'account::ip';
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_users_iphistory` WHERE `user_id` = '" . $user_id . "'"), 0);
    $tpl_data['total'] = $total;
    $tpl_data['items'] = [];
    if ($total) {
        $req = mysql_query("SELECT * FROM `cms_users_iphistory` WHERE `user_id` = '" . $user_id . "' ORDER BY `time` DESC LIMIT $start, $kmess");
        while ($res = mysql_fetch_assoc($req)) {
            $tpl_data['items'][] = [
                'search_url' => ($rights ? (SITE_URL . '/' . $set['admp'] . '/search-ip?mod=history&amp;ip=' . long2ip($res['ip'])) : ''),
                'ip'  => long2ip($res['ip']),
                'time' => functions::display_date($res['time'])
            ];
        }
    }
    $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('ip?page=', $start, $total, $kmess) : '');
}