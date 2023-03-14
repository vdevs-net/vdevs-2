<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
if ($id) {
    if ($rights == 3 || $rights >= 6) {
        require(ROOTPATH . 'system/header.php');
        $req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_closed` = "0"'));
        if (mysql_num_rows($req)) {
            $thread_name = mysql_result($req, 0);
            if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type`="1" AND `topic` = "' . $id . '"'), 0)) {
                if (IS_POST && TOKEN_VALID) {
                    mysql_query('DELETE FROM `cms_forum_vote` WHERE `topic` = "' . $id . '"');
                    mysql_query('DELETE FROM `cms_forum_vote_users` WHERE `topic` = "' . $id . '"');
                    mysql_query('UPDATE `phonho_threads` SET  `realid` = "0"  WHERE `id` = "' . $id . '"');
                    mysql_query('OPTIMIZE TABLE `cms_forum_vote`, `cms_forum_vote_users`');
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['voting_deleted'];
                    $tpl_data['back_url'] = SITE_URL . '/forum/threads/' . functions::makeUrl($thread_name) . '.' . $id . '/';
                    $tpl_data['back_text'] = $lng['back'];
                } else {
                    $tpl_file = 'page.confirm';
                    $tpl_data['form_action'] = 'vote-delete';
                    $tpl_data['confirm_text'] = $lng['voting_delete_warning'];
                    $tpl_data['cancel_url'] = SITE_URL . '/forum/threads/' . functions::makeUrl($thread_name) . '.' . $id . '/';
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
                $tpl_data['back_url'] = SITE_URL . '/forum/threads/' . functions::makeUrl($thread_name) . '.' . $id . '/';
                $tpl_data['back_text'] = $lng['back'];
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_topic_deleted'];
            $tpl_data['back_url'] = SITE_URL . '/forum/';
            $tpl_data['back_text'] = $lng['to_forum'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}
