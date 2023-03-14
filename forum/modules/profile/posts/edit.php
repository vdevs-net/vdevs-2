<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $req = mysql_query('SELECT `cms_profile_posts`.*, `users`.`account` as `profile_account`, `users`.`rights` as `profile_rights`, `author`.`account` as `author_account`, `author`.`rights` as `author_rights` FROM `cms_profile_posts` LEFT JOIN `users` ON `users`.`id` = `cms_profile_posts`.`parent_id` LEFT JOIN `users` AS `author` ON `author`.`id` = `cms_profile_posts`.`user_id` WHERE `cms_profile_posts`.`id` = "' . $id . '" AND `cms_profile_posts`.`type` = "' . TYPE_POST . '" LIMIT 1');
    if (mysql_num_rows($req)) {
        $res = mysql_fetch_assoc($req);
        if ($user_id == $res['user_id'] || ($rights >= RIGHTS_ADMIN && $rights > $res['author_rights'] && $rights > $res['profile_rights']) || $rights == RIGHTS_SUPER_ADMIN) {
            $error = false;
            $text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
            $privacy = isset($_POST['privacy']) ? abs(intval($_POST['privacy'])) : $res['privacy'];
            if (IS_POST && TOKEN_VALID) {
                $flood = functions::antiflood();
                if (empty($text)) {
                    $error = 'Bạn chưa nhập nội dung!';
                } elseif (! array_key_exists($privacy, $privacy_list)) {
                    $error = $lng['error_wrong_data'];
                } elseif ($flood) {
                    $error = $lng['error_flood'] . ' ' . $flood . $lng['sec'];
                }
                if (!$error) {
                    mysql_query('UPDATE `cms_profile_posts` SET `text` = "' . mysql_real_escape_string($text) . '", `privacy` = "' . $privacy . '" WHERE `id` ="' . $id . '"');
                    mysql_query('UPDATE `users` SET `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
                }
                header('Location: ' . SITE_URL . '/profile/' . $res['profile_account'] . '.' . $res['parent_id'] . '/'); exit;
            }
            $tpl_file = 'profile::posts.edit';
            $tpl_data['error'] = ($error ? functions::display_error($error): '');
            $tpl_data['form_title'] = ($user_id == $res['parent_id'] ? 'Chỉnh sửa bài đăng' : 'Chỉnh sửa bài đăng trên tường nhà ' . $res['profile_account']);
            $tpl_data['form_action'] = SITE_URL . '/profile/posts/' . $id . '/edit';
            $tpl_data['bbcode_editor']  = bbcode::auto_bb('form', 'text');
            $tpl_data['input_text'] = functions::checkout($res['text']);
            $tpl_data['privacy_option'] = '';
            foreach ($privacy_list as $key => $value) {
                $tpl_data['privacy_option'] .= '<option value="' . $key . '" ' . ($privacy == $key ? 'selected="selected" ' : '') . '>' . $value . '</option>';
            }

            $tpl_data['back_url'] = SITE_URL . '/profile/' . $res['profile_account'] . '.' . $res['parent_id'] . '/';
        } else {
            $error_rights = true;
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_wrong_data'];
    }
}