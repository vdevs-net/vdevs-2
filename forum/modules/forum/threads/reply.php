<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $_error = false;
    // Deny access to specific situations
    if ((!$user_id || isset($ban['1']) || isset($ban['11']) || $set['mod_forum'] == 3) && $rights < RIGHTS_SUPER_MODER) {
        $_error = $lng['access_forbidden'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
    } else {
        $agn1 = strtok($agn, ' ');
        $type = mysql_query('SELECT * FROM `phonho_threads` WHERE `id` = "' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
        if (mysql_num_rows($type)) {
            $type1 = mysql_fetch_assoc($type);
            $thread_url = '/forum/threads/' . functions::makeUrl($type1['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            $headmod = 'forum-theme-' . $id . '-1';
            $page_title = $type1['text'];

            $br_res = true;
            $allow = 0;
            $parent = (int) $type1['refid'];
            while ($parent != 0 && $br_res) {
                $br_res = mysql_fetch_assoc(mysql_query('SELECT `type`, `allow`, `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $parent . '" LIMIT 1'));
                if ($br_res) {
                    if ($br_res['type'] == 'f') {
                        if ($br_res['refid'] == 0) {
                            $tree[] = ['/forum/#' . functions::makeUrl($br_res['forum_name']) . '-' . $parent, $br_res['forum_name']];
                        } else {
                            $tree[] = ['/forum/categories/' . functions::makeUrl($br_res['forum_name']) . '.' . $parent . '/', $br_res['forum_name']];
                        }
                    } else {
                        $tree[] = ['/forum/forums/' . functions::makeUrl($br_res['forum_name']) . '.' . $parent . '/', $br_res['forum_name']];
                        $allow = intval($br_res['allow']);
                    }
                }
                $parent = $br_res['refid'];
            }
            $tree[] = ['/forum/', $lng['forum']];
            krsort($tree);
            $tree[] = [$thread_url, $type1['text']];

            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($tree);
            $_breadcrumb = $breadcrumb->out();

            if ($allow == 4 && $rights < RIGHTS_ADMIN) {
                $_error = $lng['access_forbidden'];
                $tpl_data['back_url'] = SITE_URL . '/forum/';
            } else {
                // Check for flood
                $flood = functions::antiflood();
                if ($flood) {
                    $_error = $lng['error_flood'] . ' ' . $flood . $lng['sec'];
                    $tpl_data['back_url'] = $thread_abs_url;
                } else {
                    // Adding a simple message
                    if ($type1['thread_closed'] == 1 && $rights < 7) {
                        // Проверка, закрыта ли тема
                        $_error = $lng['error_topic_closed'];
                        $tpl_data['back_url'] = $thread_abs_url;
                    }
                }
            }
        } else {
            $_error = $lng['error_topic_deleted'];
            $tpl_data['back_url'] = SITE_URL . '/forum/';
        }
    }

    if (!$_error) {
        $error = false;
        $error_img = false;
        $msg = isset($_POST['msg']) ? functions::checkin($_POST['msg']) : '';
        if (isset($_POST['add_image']) && TOKEN_VALID) {
            reset($_FILES);
            $file = current($_FILES);
            if (is_uploaded_file($file['tmp_name'])) {
                $imgur = new imgur();
                $imgur->upload($file, 'file');
                if ($imgur->uploaded) {
                    $msg = trim($msg . "\r\n" . '[img]' . $imgur->data['link'] . '[/img]');
                    mysql_query('INSERT INTO `cms_images` SET `user_id` = "' . $user_id . '", `time` = "' . SYSTEM_TIME . '", `size` = "' . $imgur->data['size'] . '", `width` = "' . $imgur->data['width'] . '", `height` = "' . $imgur->data['height'] . '", `link` = "' . $imgur->data['link'] . '", `deleteHash` = "' . $imgur->data['deletehash'] . '"');
                } else {
                    $error_img = $imgur->error;
                }
            }
        }
        if (isset($_POST['submit']) && TOKEN_VALID) {
            // Check the minimum length
            if (mb_strlen($msg) < MIN_FORUM_MESSAGE_LENGTH) {
                $error =  $lng['error_message_short'];
            }
            if (!$error) {
                // Handle links
                $msg = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
                // Check, if the message is not repeated?
                $req = mysql_query('SELECT `text` FROM `phonho_posts` WHERE `user_id` = "' . $user_id . '" ORDER BY `time` DESC LIMIT 1');
                if (mysql_num_rows($req) > 0) {
                    $res = mysql_fetch_assoc($req);
                    if ($msg == $res['text']) {
                        $error = $lng['error_message_exists'];
                    }
                }
            }
            if (!$error) {
                // Add a message on the base
                mysql_query('INSERT INTO `phonho_posts` SET
                    `refid` = "' . $id . '",
                    `time` = "' . SYSTEM_TIME . '",
                    `user_id` = "' . $user_id . '",
                    `from` = "' . $login . '",
                    `ip` = "' . core::$ip . '",
                    `ip_via_proxy` = "' . core::$ip_via_proxy . '",
                    `soft` = "' . mysql_real_escape_string($agn1) . '",
                    `text` = "' . mysql_real_escape_string($msg) . '"
                ');
                $fadd = mysql_insert_id();
                mysql_query('UPDATE `phonho_threads` SET `time` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $id . '"');
                // Update user statistics
                mysql_query('UPDATE `users` SET `postforum` = "' . ($datauser['postforum'] + 1) . '", `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
                // Compute, which page gets added post
                $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $id . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0) / $kmess);
                if (isset($_POST['addfiles'])) {
                    header('Location: ' . SITE_URL . '/forum/posts/' . $fadd . '/addfile');
                } else {
                    header('Location: ' . $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $fadd);
                }
                exit;
            }
        }
        require(ROOTPATH . 'system/header.php');
        $tpl_file = 'forum::threads.reply';
        $show_rules = false;
        if ($datauser['postforum'] == 0) {
            if (!isset($_GET['yes'])) {
                $lng = array_merge($lng, core::load_lng('faq'));
                $show_rules = true;
                $tpl_data['agree_url'] = $thread_abs_url . 'reply?yes';
                $tpl_data['deny_url'] = $thread_abs_url;
            }
        }
        $tpl_data['show_rules'] = $show_rules;
        if (!$show_rules) {
            $tpl_data['preview_mode'] = false;
            $tpl_data['preview_post'] = '';
            $tpl_data['error'] = ($error ? functions::display_error($error) : '');
            $tpl_data['error_img'] = ($error_img ? functions::display_error($error_img) : '');
            if ($msg && isset($_POST['preview'])) {
                $tpl_data['preview_mode'] = true;
                // Handle links
                $msg_pre = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
                $tpl_data['preview_post'] = functions::checkout($msg_pre, 3, 1, 1);
            }
            $tpl_data['form_action'] = 'reply' . (isset($_GET['yes']) ? '?yes' : '');
            $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'msg');
            $tpl_data['input_message'] = functions::checkout($msg);
            $tpl_data['add_image_mode'] = false;
            $tpl_data['recent_images'] = [];
            if (isset($_POST['add_image'])) {
                $tpl_data['add_image_mode'] = true;
                $tpl_data['recent_images'] = functions::get_recent_images();
            }
            $tpl_data['add_file_check'] = (isset($_POST['addfiles']) ? ' checked="checked"' : '');
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = functions::display_error($_error);
        $tpl_data['back_text'] = $lng['back'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}