<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights >= RIGHTS_ADMIN) {
        $req = mysql_query('SELECT `refid`, `post_deleted` FROM `phonho_posts` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            // Preliminary checks
            $res = mysql_fetch_assoc($req);
            $thread_req = mysql_query('SELECT `thread_closed`, `thread_deleted`, `text`, `first_post_id` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '"' . ($rights > RIGHTS_ADMIN ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
            if (mysql_num_rows($thread_req)) {
                $topic = mysql_fetch_assoc($thread_req);

                $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <= "' . $id . '"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0) / $kmess);
                $thread_url = '/forum/threads/' . functions::makeUrl($topic['text']) . '.' . $res['refid'] . '/';
                $thread_abs_url = SITE_URL . $thread_url;
                if ($res['post_deleted'] && !$topic['thread_deleted']) {
                    // Undelete post
                    if ($id == $topic['first_post_id']) {
                        mysql_query('UPDATE `phonho_threads` SET `thread_deleted` = "0", `thread_deleted_user` = "' . $login . '" WHERE `id` = "' . $res['refid'] . '"');
                    }
                    mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "0", `post_deleted_user` = "' . $login . '" WHERE `id` = "' . $id . '"');
                    mysql_query('UPDATE `cms_forum_files` SET `del` = "0" WHERE `post` = "' . $id . '" LIMIT 1');
                }
                header('Location: ' . $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id); exit;
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = functions::display_error($lng['error_topic_deleted']);
                $tpl_data['back_url'] = SITE_URL . '/forum/';
                $tpl_data['back_text'] = $lng['back'];
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($lng['error_post_deleted']);
            $tpl_data['back_url'] = SITE_URL . '/forum/';
            $tpl_data['back_text'] = $lng['back'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}