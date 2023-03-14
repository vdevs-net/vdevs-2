<?php
defined('_MRKEN_CMS') or die('Error: restricted access');


if ($user_id) {
    define('PRIVACY_ALL', 0);
    define('PRIVACY_ONLY_ME', 2);
    define('TYPE_POST', 1);
    define('TYPE_COMMENT', 2);
    $privacy_list = [
        0 => 'Mọi người',
        2 => 'Chỉ mình tôi'
    ];
    $lng = array_merge($lng, core::load_lng('profile'));
    if ($module_file == 'profile') {
        if ($id) {
            if ($id != $user_id) {
                $user = functions::get_user($id);
                if (!$user) {
                    $module_error = $lng['user_does_not_exist'];
                }
            } else {
                $user = $datauser;
                $user['lastdate'] = SYSTEM_TIME;
            }
            $profile_url = SITE_URL . '/profile/' . $user['account'] . '.' . $user['id'] . '/';
            $menu = [
                '<a href="' . $profile_url . '">Trang cá nhân</a>',
                '<a href="' . $profile_url . 'information">' . $lng['information'] . '</a>',
                '<a href="' . $profile_url . 'activity">' . $lng['activity'] . '</a>'
            ];
            if ($user['id'] != $user_id) {
                if ($rights == 9) {
                    $menu[] = '<a href="' . SITE_URL . '/shop/history?user=' . $user['id'] . '">Lịch sử giao dịch</a>';
                }
                if (empty($ban['1']) && empty($ban['3'])) {
                    $menu[] = '<a href="' . SITE_URL . '/messages/write?id=' . $user['id'] . '">' . $lng['message'] . '</a>';
                }
                if ($rights > $user['rights']) {
                    $menu[] = '<a href="' . SITE_URL . '/' . $set['admp'] . '/usr?id=' . $user['id'] . '">Moderator Tools</a>';
                }
            }

            $tpl_data['profileCoverVariable'] = [
                'user_cover_photo'  => functions::getCover($user['id']),
                'change_cover_url'  => ($user_id == $user['id'] ? (SITE_URL . '/account/cover') : ''),
                'user_avatar'       => functions::get_avatar($user['id']),
                'change_avatar_url' => ($user_id == $user['id'] ? (SITE_URL . '/account/avatar') : ''),
                'user_name'         => $user['account'],
                'user_status'       => ($user['status'] ? htmlspecialchars($user['status']) : ''),
                'menu'              => $menu
            ];
        } else {
            $module_error = $lng['error_wrong_data'];
        }
    }
} else {
    $module_error = $lng['access_guest_forbidden'];
}