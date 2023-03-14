<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

// user Profile
$headmod = 'profile-' . $user['id'];
$page_title = 'Trang cá nhân - ' . $user['account'];
require(ROOTPATH . 'system/header.php');

$tpl_file = 'profile::profile';
// Notice of birthday
$tpl_data['is_birthday'] = ($user['dayb'] == date('j', SYSTEM_TIME) && $user['monthb'] == date('n', SYSTEM_TIME));

// If the user is waiting for confirmation of registration, receive a reminder
$tpl_data['not_activated'] = ($rights >= 7 && !$user['preg'] && empty($user['regadm']));

$tpl_data['form_action'] = $profile_url . 'write';
$tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'text');
$tpl_data['form_title'] = ($user_id == $user['id'] ? 'Bạn đang nghĩ gì?' : 'Viết gì đó cho ' . $user['account']);
$token = mt_rand(10000, 99999);
$_SESSION['token'] = $token;
$tpl_data['token'] = $token;

$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_profile_posts` WHERE `type` = "' . TYPE_POST . '" AND `parent_id` = "' . $user['id'] . '"' .
        ($user_id == $user['id'] || $rights == 9 ? '' : ' AND (`cms_profile_posts`.`privacy` = "' . PRIVACY_ALL . '" OR (`cms_profile_posts`.`privacy` = "' . PRIVACY_ONLY_ME . '" AND (`cms_profile_posts`.`user_id` = "' . $user_id . '" OR `cms_profile_posts`.`parent_id` = "' . $user_id . '")))') .
        ''), 0);
$start = functions::fixStart($start, $total, $kmess);
$max_page = ceil($total / $kmess);
if ($page > $max_page) {
    $page = $max_page;
}
$tpl_data['total'] = $total;
$tpl_data['pagination'] =  ($total > $kmess ? functions::display_pagination($profile_url . 'page-', $start, $total, $kmess) : '');
$tpl_data['posts'] = [];
if ($total) {
    $posts = mysql_query('SELECT `cms_profile_posts`.*, `users`.`rights`, `users`.`account` FROM `cms_profile_posts` LEFT JOIN `users` ON `users`.`id` = `cms_profile_posts`.`user_id` WHERE `cms_profile_posts`.`type` = "' . TYPE_POST . '" AND `cms_profile_posts`.`parent_id` = "' . $user['id'] . '"' .
        ($user_id == $user['id'] || $rights == 9 ? '' : ' AND (`cms_profile_posts`.`privacy` = "' . PRIVACY_ALL . '" OR (`cms_profile_posts`.`privacy` = "' . PRIVACY_ONLY_ME . '" AND (`cms_profile_posts`.`user_id` = "' . $user_id . '" OR `cms_profile_posts`.`parent_id` = "' . $user_id . '")))') .
        ' ORDER BY `id` DESC LIMIT ' . $start . ', ' . $kmess);
    while ($res = mysql_fetch_assoc($posts)) {
        $tpl_data['posts'][] = [
            'user_avatar'      => functions::get_avatar($res['user_id']),
            'user_html_class'  => 'user_' . $res['rights'],
            'user_profile_url' => SITE_URL . '/profile/' . $res['account'] . '.' . $res['user_id'] . '/',
            'user_name'        => $res['account'],
            'time'             => functions::display_date($res['time']),
            'text'             => functions::checkout($res['text'], 1, 2, 1),
            'privacy'          => ($res['privacy'] == PRIVACY_ALL ? 'Mọi người' : ($res['privacy'] == PRIVACY_ONLY_ME ? 'Chỉ mình tôi' : '')),
            'edit_url'         => ($user_id == $res['user_id'] || ($rights >= 7 && $rights > $res['rights']) || $rights == 9 ? SITE_URL . '/profile/posts/' . $res['id'] . '/edit' : ''),
            'delete_url'       => ($user_id == $res['user_id'] || $user_id == $user['id'] || ($rights >= 7 && $rights > $res['rights']) || $rights == 9 ? SITE_URL . '/profile/posts/' . $res['id'] . '/delete' : '')
        ];
    }
}