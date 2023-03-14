<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
if ($id) {
    if ($user_id) {
        $req = mysql_query('SELECT `user_id`, `refid` FROM `phonho_posts` WHERE `id`="' . $id . '" AND `post_deleted` ="0" LIMIT 1');
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);	
            $text = mysql_result(mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id`="' . $res['refid'] . '" LIMIT 1'), 0);
            $threads_abs_url = SITE_URL . '/forum/threads/' . functions::makeUrl($text) . '.' . $res['refid'] . '/';
            $post_url = SITE_URL . '/forum/posts/' . $id . '/';
            $cpg = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <= "' . $id . '"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0) / $kmess);
            if($res['user_id'] != $user_id ) {
                /* check if liked */
                $chkl = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_likes` WHERE `type`="1" AND `user_like`="'. $user_id .'" AND `sub_id`="'. $id .'"'), 0);
                if ($chkl) {
                    mysql_query('DELETE FROM `cms_likes` WHERE `type`="1" AND `user_like`="' . $user_id . '" AND `sub_id`="'. $id .'"');
                    mysql_query('DELETE FROM `cms_mail` WHERE `user_id`="0" AND `sys`="1" AND `from_id` = "' . $res['user_id'] . '" AND `text` LIKE "%[url=' . SITE_URL . '/profile/' . $datauser['account'] . '.' . $user_id . '/]' . $login . '[/url]%" AND `text` LIKE "%[url=' . $post_url . ']%" LIMIT 1');
                } else {
                    mysql_query('INSERT INTO `cms_likes` SET `type`="1", `user_like` = "'.$user_id.'", `user_id` = "' . $res['user_id'] .'", `sub_id`="'. $id .'", `parent_id` = "' . $res['refid'] . '"');
                    /* Send notification */
                    $msg = 'Diễn đàn: [url=' . SITE_URL . '/profile/' . $datauser['account'] . '.' . $user_id . '/]' . $login . '[/url] đã thích bài viết của bạn tại chủ đề [url=' . $post_url . ']' . $text . '[/url].';
                    mysql_query('INSERT INTO `cms_mail` SET `user_id` = "0", `from_id` = "' . $res['user_id'] . '", `text` = "'. mysql_real_escape_string($msg) .'", `time` = "' . SYSTEM_TIME . '", `sys` = "1", `them` = "Thông báo"');
                }
                header('location: ' . $threads_abs_url . 'page-' . $cpg . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id); exit;
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = 'You can not like your post!';
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_post_deleted'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}