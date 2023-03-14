<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights >= 7) {
        $req = mysql_query('SELECT `text`, `thread_deleted` FROM `phonho_threads` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);
            if ($res['thread_deleted']) {
                mysql_query('UPDATE `phonho_threads` SET `thread_deleted` = "0", `thread_deleted_user` = "' . $login . '" WHERE `id` = "' . $id . '"');
                mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "0", `post_deleted_user` = "" WHERE `refid` = "' . $id . '"');
                mysql_query('UPDATE `cms_forum_files` SET `del` = "0" WHERE `topic` = "' . $id . '"');
            }
            header('Location: ' . SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $id . '/'); exit;
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_topic_deleted'];
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