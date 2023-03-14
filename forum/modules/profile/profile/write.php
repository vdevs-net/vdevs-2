<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$error = false;
$text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
$privacy = isset($_POST['privacy']) ? abs(intval($_POST['privacy'])) : 0;

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
if (empty($ban)) {
    if (IS_POST && isset($_SESSION['token']) && mb_strlen($token) > 4 && $token == $_SESSION['token']) {
        $flood = functions::antiflood();
        if (empty($text)) {
            $error = 'Bạn chưa nhập nội dung!';
        } elseif (! array_key_exists($privacy, $privacy_list)) {
            $error = $lng['error_wrong_data'];
        } elseif ($flood) {
            $error = $lng['error_flood'] . ' ' . $flood . $lng['sec'];
        }
        if (!$error) {
            mysql_query('INSERT INTO `cms_profile_posts` SET
                `user_id` = "' . $user_id . '",
                `parent_id` = "' . $user['id'] . '",
                `time` = "' . SYSTEM_TIME . '",
                `type` = "' . TYPE_POST . '",
                `text` = "' . mysql_real_escape_string($text) . '",
                `privacy` = "' . $privacy . '"
            ');
            mysql_query('UPDATE `users` SET `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
            header('Location: ' . $profile_url); exit;
        }
    }
} else {
    $error = 'Hiện tại bạn không thể thực hiện hành động này!';
}
$tpl_file = 'profile::write';
$tpl_data['error'] = ($error ? functions::display_error($error): '');
$tpl_data['form_title'] = ($user_id == $user['id'] ? 'Bạn đang nghĩ gì?' : 'Viết gì đó cho ' . $user['account']);
$tpl_data['form_action'] = $profile_url . 'write';
$tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'text');
$tpl_data['privacy_option'] = '';
foreach ($privacy_list as $key => $value) {
    $tpl_data['privacy_option'] .= '<option value="' . $key . '" ' . ($privacy == $key ? 'selected="selected" ' : '') . '>' . $value . '</option>';
}
$token = mt_rand(10000, 99999);
$_SESSION['token'] = $token;
$tpl_data['token'] = $token;
