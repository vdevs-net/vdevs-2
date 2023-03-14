<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($user_id) {
        $req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $thread_name = mysql_result($req, 0);
            $req = mysql_query('SELECT `post_id`, `time` FROM `cms_forum_rdm` WHERE `topic_id` = "' . $id . '" AND `user_id` = "' . $user_id . '" LIMIT 1');
            $st = '';
            if (mysql_num_rows($req)) {
                $res = mysql_fetch_assoc($req);
                $count_unread_post = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $id . '" AND `time` > "'. $res['time'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` = "0"')), 0);
                $count_read_post = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $id . '" AND `time` <= "'. $res['time'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` = "0"')), 0);
                $post_id = $res['post_id'];
                if ($count_unread_post) {
                    $count_read_post++;
                    $post_id = mysql_result(mysql_query('SELECT `id` FROM `phonho_posts` WHERE `refid` = "' . $id . '" AND `time` > "'. $res['time'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` = "0"') . ' ORDER BY `time` ASC LIMIT 1'), 0);
                }
                $cpg = ceil($count_read_post / $kmess);
                $st = ($allow_js_scroll ? '?st=' : '#') . 'post' . $post_id;
            } else {
                $cpg = 1;
            }
            $thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($thread_name) . '.' . $id . '/page-' . $cpg . $st;
            header('Location: ' . $thread_url); exit;
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}