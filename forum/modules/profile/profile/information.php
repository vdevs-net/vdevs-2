<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'profile-' . $user['id'];
// For details, contact details
$page_title = $user['account'] . ': ' . $lng['information'];
require(ROOTPATH . 'system/header.php');
$tpl_file = 'profile::information';

$tpl_data['user_name'] = (empty($user['imname']) ? '' : $user['imname']);
$tpl_data['user_coin'] = $user['coin'];
$tpl_data['user_gold'] = $user['gold'];
$tpl_data['user_sex'] = ($user['sex'] == 'm' ? 'Nam' : 'Nữ');
$tpl_data['user_birthday'] = (empty($user['dayb']) ? '' : sprintf('%02d', $user['dayb']) . '.' . sprintf('%02d', $user['monthb']) . '.' . $user['yearb']);
$tpl_data['user_address'] = (empty($user['live']) ? '' : functions::checkout($user['live']));
$tpl_data['user_about'] = (empty($user['about']) ? '' : functions::checkout($user['about'], 1, 1, 2));
$tpl_data['hide_contact_set'] = !$user['mailvis'];
$tpl_data['show_contact'] = false;
if ($user['mailvis'] || $rights >= 7 || $user['id'] == $user_id) {
    $tpl_data['show_contact'] = true;
    $tpl_data['user_mobile'] = (empty($user['mobile']) ? '' : ('0' . $user['mobile']));
    $tpl_data['user_email'] = (empty($user['mail']) ? '' : functions::checkout($user['mail']));
}
$tpl_data['user_facebook'] = (empty($user['facebook']) ? '' : htmlspecialchars($user['facebook']));
// stats
$tpl_data['user_register_status'] = '';
if ($rights >= 7) {
    if (!$user['preg'] && empty($user['regadm'])) {
        $tpl_data['user_register_status'] = $lng['awaiting_registration'];
    } elseif ($user['preg'] && !empty($user['regadm'])) {
        $tpl_data['user_register_status'] = $lng['registration_approved'] . ': ' . $user['regadm'];
    } else {
        $tpl_data['user_register_status'] = $lng['registration_free'];
    }
}
$tpl_data['user_register_date'] = functions::display_date($user['datereg']);
$tpl_data['user_online_time'] = ceil($user['total_on_site'] / 60) . ' ' . $lng['minutes'];
$tpl_data['user_last_visit'] = SYSTEM_TIME - 300 > $user['lastdate'] ? functions::display_date($user['lastdate']) : false;
// Ban count
$tpl_data['user_ban_count'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_ban_users` WHERE `user_id` = "' . $user['id'] . '"'), 0);
$tpl_data['user_ban_url'] = $profile_url . 'ban';
$tpl_data['user_post_forum'] = $user['postforum'];
$tpl_data['user_post_forum_url'] = $profile_url . 'activity';
$tpl_data['user_comment'] = $user['komm'];
$tpl_data['points'] = array(
    50,
    100,
    500,
    1000,
    5000
);
$tpl_data['fields'] = [
    'postforum' => $lng['forum'],
    'komm' => $lng['comments']
];
$tpl_data['user_fields'] = [
    'postforum' => $user['postforum'],
    'komm' => $user['komm']
];