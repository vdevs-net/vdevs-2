<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$_error = false;
// Check rights
if (!$id || !$user_id || isset($ban['1']) || isset($ban['11']) || (!$rights && $set['mod_forum'] == 3)) {
    $_error = $lng['access_forbidden'];
} else {
    $req_r = mysql_query('SELECT `id`, `refid`, `forum_name`, `allow` FROM `phonho_forums` WHERE `id` = "' . $id . '" AND `type` = "r" LIMIT 1');
    if (mysql_num_rows($req_r)) {
        $page_title = $lng['new_topic'];
        $res_r = mysql_fetch_assoc($req_r);
        $forum_url = '/forum/forums/' . functions::makeUrl($res_r['forum_name']) . '.' . $id . '/';
        $forum_abs_url = SITE_PATH . $forum_url;

        get_breadcrumb($res_r['refid'], [$forum_url, $res_r['forum_name']], $_breadcrumb);
        if ($res_r['allow'] == 4 && $rights != RIGHTS_MODER_FORUM && $rights < RIGHTS_SUPER_MODER) {
            $_error = 'Bạn không thể tạo chủ đề trong chuyên mục này!';
            $tpl_data['back_url'] = $forum_abs_url;
            $tpl_data['back_text'] = $lng['back'];
        } else {
            // Check for flood
            $flood = functions::antiflood();
            if ($flood) {
                $_error = $lng['error_flood'] . ' ' . $flood . ' ' . $lng['sec'];
                $tpl_data['back_url'] = $forum_abs_url;
                $tpl_data['back_text'] = $lng['back'];
            }
        }
    } else {
        $_error = $lng['error_wrong_data'];
    }
}

if (!$_error) {

    $th = isset($_POST['th']) ? functions::checkin(mb_substr(trim($_POST['th']), 0, 255), 1) : '';
    $prefix = isset($_POST['prefix']) ? abs(intval($_POST['prefix'])) : 0;
    if (!array_key_exists($prefix, $prefixs)) {
        $prefix = 0;
    }
    $msg = isset($_POST['msg']) ? functions::checkin($_POST['msg']) : '';
    $tags = isset($_POST['tags']) ? functions::checkin($_POST['tags']) : '';
    $tags2 = isset($_POST['tags']) ? functions::forum_tags($tags) : '';
    $portal = isset($_POST['portal']) && $rights >= 7 ? SYSTEM_TIME : 0;
    if (!empty($_FILES['image']['name'])) {
        if (isset($_POST['add_image']) && TOKEN_VALID) {
            $imgur = new imgur();
            $imgur->upload($_FILES['image'], 'file');
            if ($imgur->uploaded) {
                $msg = trim($msg . "\r\n" . '[img]' . $imgur->data['link'] . '[/img]');
                mysql_query('INSERT INTO `cms_images` SET `user_id` = "' . $user_id . '", `time` = "' . SYSTEM_TIME . '", `size` = "' . $imgur->data['size'] . '", `width` = "' . $imgur->data['width'] . '", `height` = "' . $imgur->data['height'] . '", `link` = "' . $imgur->data['link'] . '", `deleteHash` = "' . $imgur->data['deletehash'] . '"');
            }
        }
    }
    $error = array();
    if (isset($_POST['submit']) && TOKEN_VALID) {
        if (empty($th)) {
            $error[] = $lng['error_topic_name'];
        } elseif (mb_strlen($th) < 16) {
            $error[] = $lng['error_topic_name_lenght'];
        }
        if (empty($msg)) {
            $error[] = $lng['error_empty_message'];
        } elseif (mb_strlen($msg) < MIN_FORUM_MESSAGE_LENGTH) {
            $error[] = $lng['error_message_short'];
        }
    	
        if (!$error) {
            // Прверяем, есть ли уже такая тема в текущем разделе?
            if (mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE  `refid` = "' . $id . '" AND `text` = "' . mysql_real_escape_string($th) . '"'), 0) > 0) {
                $error[] = $lng['error_topic_exists'];
            } else {
                // Проверяем, не повторяется ли сообщение?
                $req = mysql_query('SELECT `text` FROM `phonho_posts` WHERE `user_id`="' . $user_id . '" ORDER BY `time` DESC LIMIT 1');
                if (mysql_num_rows($req) > 0) {
                    $res = mysql_fetch_array($req);
                    if ($msg == $res['text']) {
                        $error[] = $lng['error_message_exists'];
                    }
                }
            }
        }
        if (!$error) {
            $msg = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);

            // Добавляем тему
            mysql_query('INSERT INTO `phonho_threads` SET
                `refid` = "' . $id . '",
                `time` = "' . SYSTEM_TIME . '",
                `user_id` = "' . $user_id . '",
                `from` = "' . $login . '",
                `prefix` = "' . $prefix . '",
                `text` = "' . mysql_real_escape_string($th) . '",
                `soft` = "' . mysql_real_escape_string($tags2) . '",
                `portal` = "' . $portal . '"
            ');
            $rid = mysql_insert_id();

            // Добавляем текст поста
            mysql_query('INSERT INTO `phonho_posts` SET
                `refid` = "' . $rid . '",
                `time` = "' . SYSTEM_TIME . '",
                `user_id` = "' . $user_id . '",
                `from` = "' . $login . '",
                `ip` = "' . core::$ip . '",
                `ip_via_proxy` = "' . core::$ip_via_proxy . '",
                `soft` = "' . mysql_real_escape_string($agn) . '",
                `text` = "' . mysql_real_escape_string($msg) . '"
            ');
            $postid = mysql_insert_id();
            // Update user post count
            $fpst = $datauser['postforum'] + 1;
            mysql_query('UPDATE `users` SET `postforum` = "' . $fpst . '", `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
            mysql_query('UPDATE `phonho_threads` SET `first_post_id` = "' . $postid . '" WHERE `id` = "' . $rid . '"');
            // update form read
            mysql_query('INSERT INTO `cms_forum_rdm` SET `topic_id` = "' . $rid . '", `post_id` = "' . $postid . '", `user_id` = "' . $user_id . '", `time`="' . SYSTEM_TIME . '"');
            if (isset($_POST['addfiles'])) {
                header('Location: ' . SITE_PATH . '/forum/posts/' . $postid . '/addfile');
            } else {
                header('Location: ' . SITE_PATH . '/forum/threads/' . functions::makeUrl($th) . '.' . $rid . '/');
            }
            exit;
        }
    }
    
    require(ROOTPATH . 'system/header.php');
    $tpl_file = 'forum::forums.new-thread';
    $tpl_data['error'] = ($error ? functions::display_error($error) : '');
    $show_rules = false;
    if ($datauser['postforum'] == 0) {
        if (!isset($_GET['yes'])) {
            $lng = array_merge($lng, core::load_lng('faq'));
            $show_rules = true;
            $tpl_data['agree_url'] = $forum_abs_url . 'new-thread?yes';
            $tpl_data['deny_url'] = $forum_abs_url;
        }
    }
    $tpl_data['show_rules'] = $show_rules;
    if (!$show_rules) {
        $tpl_data['preview_mode'] = false;
        if ($msg && isset($_POST['preview'])) {
            $tpl_data['preview_mode'] = true;
            $msg_pre = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
            $tpl_data['preview_post'] = functions::checkout($msg_pre, 3, 1, 1);
        }
        $tpl_data['form_action'] = $forum_abs_url . 'new-thread' . (isset($_GET['yes']) ? '?yes' : '');
        $tpl_data['input_thread_name'] = functions::checkout($th);
        $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'msg');
        $tpl_data['input_post'] = functions::checkout($msg);
        $tpl_data['input_tags'] = functions::checkout($tags);
        $tpl_data['prefix_option'] = '';
        foreach ($prefixs as $k => $v) {
            $tpl_data['prefix_option'] .= '<option value="' . $k . '"' . ($prefix == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
        }
        $tpl_data['add_image_mode'] = false;
        if (isset($_POST['add_image'])) {
            $tpl_data['add_image_mode'] = true;
            $tpl_data['recent_images'] = functions::get_recent_images();
        }
        $tpl_data['add_file_check'] = (isset($_POST['addfiles']) ? ' checked="checked"' : '');
        $tpl_data['add_portal_check'] = (isset($_POST['portal']) ? ' checked="checked"' : '');
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $_error;
}