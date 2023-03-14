<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['news'];
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/news', $lng['news']);
$breadcrumb->add($lng['add']);
$_breadcrumb = $breadcrumb->out();

if ($rights >= RIGHTS_SUPER_MODER) {
    $display_form = true;
    $error = array();
    $name = isset($_POST['name']) ? functions::checkin($_POST['name']) : '';
    $text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
    $forum_id = isset($_POST['forum_id']) ? functions::checkin($_POST['forum_id']) : 0;
    if (IS_POST && TOKEN_VALID) {
        if (empty($name)) {
            $error[] = $lng['error_title'];
        }
        if (empty($text)) {
            $error[] = $lng['error_text'];
        }
        $flood = functions::antiflood();
        if ($flood) {
            $error[] = $lng['error_flood'] . ' ' . $flood . ' ' . $lng['seconds'];
        }
        if (empty($error)) {
            $rid = 0;
            if ($forum_id) {
                if (mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `id` = "' . $forum_id . '" AND `type`="r" LIMIT 1'), 0)) {
                    mysql_query('INSERT INTO `phonho_threads` SET
                        `refid` = "' . $forum_id . '",
                        `time` = "' . SYSTEM_TIME . '",
                        `user_id` = "' . $user_id . '",
                        `from` = "' . $login . '",
                        `text` = "' . mysql_real_escape_string($name) . '"
                    ') or die(mysql_error());
                    $rid = mysql_insert_id();
                    mysql_query('INSERT INTO `phonho_posts` SET
                        `refid` = "' . $rid . '",
                        `time` = "' . SYSTEM_TIME . '",
                        `user_id` = "' . $user_id . '",
                        `from` = "' . $login . '",
                        `ip` = "' . $ip . '",
                        `soft` = "' . mysql_real_escape_string($agn) . '",
                        `text` = "' . mysql_real_escape_string($text) . '"
                    ') or die(mysql_error());
                }
            }
            mysql_query('INSERT INTO `news` SET
                `time` = "' . SYSTEM_TIME . '",
                `avt` = "' . $login . '",
                `name` = "' . mysql_real_escape_string($name) . '",
                `text` = "' . mysql_real_escape_string($text) . '",
                `kom` = "' . $rid . '"
            ');
            mysql_query('UPDATE `users` SET
                `lastpost` = "' . SYSTEM_TIME . '"
                WHERE `id` = "' . $user_id . '"
            ');
            $display_form = false;
            $tpl_data['page_content'] = $lng['article_added'];
            $tpl_file = 'page.success';
        }
    }
    if ($display_form) {
        $tpl_file = 'news::add';
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['form_action']   = SITE_URL . '/news/add';
        $tpl_data['input_title']   = functions::checkout($name);
        $tpl_data['input_content'] = functions::checkout($text);
        $tpl_data['input_forum_id'] = $forum_id;
        $tpl_data['categories'] = array();
        $req_cat = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "f"');
        while ($res_cat = mysql_fetch_assoc($req_cat)) {
            $tpl_data['categories'][$res_cat['id']]['name'] = functions::checkout($res_cat['forum_name']);
            $tpl_data['categories'][$res_cat['id']]['items'] = array();
            $req_forum = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "r" AND `refid` = "' . $res_cat['id'] . '"');
            while ($res_forum = mysql_fetch_assoc($req_forum)) {
                $tpl_data['categories'][$res_cat['id']]['items'][] = array(
                    'id'   => $res_forum['id'],
                    'name' => functions::checkout($res_forum['forum_name'])
                );
            }
        }
    }
} else {
    $error_rights = true;
}