<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($user_id) {
        $req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_closed` = "0"'));
        if (mysql_num_rows($req)) {
            $thread_name = mysql_result($req, 0);
            $error = false;
            $vote = isset($_POST['vote']) ? abs(intval($_POST['vote'])) : 0;
            if ($vote) {
                require(ROOTPATH . 'system/header.php');
                if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type` = "2" AND `id` = "' . $vote . '" AND `topic` = "' . $id . '"'), 0)) {
                    if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote_users` WHERE `user` = "' . $user_id . '" AND `topic` = "' . $id . '"'), 0)) {
                        $error = true;
                    } else {
                        mysql_query("INSERT INTO `cms_forum_vote_users` SET `topic` = '$id', `user` = '$user_id', `vote` = '$vote'");
                        mysql_query("UPDATE `cms_forum_vote` SET `count` = count + 1 WHERE id = '$vote'");
                        mysql_query("UPDATE `cms_forum_vote` SET `count` = count + 1 WHERE topic = '$id' AND `type` = '1'");
                        $tpl_file = 'page.success';
                        $tpl_data['page_content'] = $lng['vote_accepted'];
                        $tpl_data['back_url'] = SITE_URL . '/forum/threads/' . functions::makeUrl($thread_name) . '.' . $id . '/';
                        $tpl_data['back_text'] = $lng['back'];
                    }
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
            if ($error) {
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