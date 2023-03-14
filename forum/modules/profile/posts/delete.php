<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $req = mysql_query('SELECT `cms_profile_posts`.*, `users`.`account`, `users`.`rights` FROM `cms_profile_posts` LEFT JOIN `users` ON `users`.`id` = `cms_profile_posts`.`parent_id` WHERE `cms_profile_posts`.`id` = "' . $id . '" AND `cms_profile_posts`.`type` = "' . TYPE_POST . '" LIMIT 1');
    if (mysql_num_rows($req)) {
        $res = mysql_fetch_assoc($req);
        if ($user_id == $res['user_id'] || $user_id == $res['parent_id'] || ($rights >= RIGHTS_ADMIN && $rights > $res['rights']) || $rights == 9) {
            if (IS_POST && TOKEN_VALID) {
                mysql_query('DELETE FROM `cms_profile_posts` WHERE `id` = "' . $id . '" AND `type` = "' . TYPE_POST . '"');
                header('Location: ' . SITE_URL . '/profile/' . $res['account'] . '.' . $res['parent_id'] . '/'); exit;
            } else {
                $tpl_file = 'page.confirm';
                $tpl_data['form_action'] = SITE_URL . '/profile/posts/' . $id . '/delete';
                $tpl_data['confirm_text']  = 'Bạn có chắc chắn muốn xóa bài đăng này?';
                $tpl_data['cancel_url'] = SITE_URL . '/profile/' . $res['account'] . '.' . $res['parent_id'] . '/';
            }
        } else {
            $error_rights = true;
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_wrong_data'];
    }
}