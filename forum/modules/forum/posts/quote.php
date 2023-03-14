<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $_error = false;
    // Deny access to specific situations
    if ((!$user_id || isset($ban['1']) || isset($ban['11']) || $set['mod_forum'] == 3) && $rights < RIGHTS_ADMIN) {
        $_error = $lng['access_forbidden'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
    } else {
        $agn1 = strtok($agn, ' ');
        $type = mysql_query('SELECT * FROM `phonho_posts` WHERE `id` = "' . $id . '" AND `post_deleted` = "0" LIMIT 1');
        if (mysql_num_rows($type)) {
            $type1 = mysql_fetch_assoc($type);
            $thread_req = mysql_query('SELECT `thread_closed`, `text`, `refid` FROM `phonho_threads` WHERE `id` = "' . $type1['refid'] . '"' . ($rights > RIGHTS_ADMIN ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
            if (mysql_num_rows($thread_req)) {
                $th1 = mysql_fetch_assoc($thread_req);
                $position = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $type1['refid'] . '" AND `id` <= "' . $id . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` = "0"')), 0);
                $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $type1['refid'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` = "0"')), 0) / $kmess);
                $thread_url = '/forum/threads/' . functions::makeUrl($th1['text']) . '.' . $type1['refid'] . '/';
                $thread_abs_url = SITE_URL . $thread_url;
                $headmod = 'forum-theme-' . $id . '-2';
                // The resulting structure Forum
                $res = true;
                $allow = 0;
                $parent = (int) $th1['refid'];
                while ($parent != 0 && $res) {
                    $res = mysql_fetch_assoc(mysql_query('SELECT `type`, `allow`, `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $parent . '" LIMIT 1'));
                    if ($res) {
                        if ($res['type'] == 'f') {
                            if ($res['refid'] == 0) {
                                $tree[] = ['/forum/#' . functions::makeUrl($res['forum_name']) . '-' . $parent, $res['forum_name']];
                            } else {
                                $tree[] = ['/forum/categories/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']];
                            }
                        } else {
                            $tree[] = ['/forum/forums/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']];
                            $allow = intval($res['allow']);
                        }
                    }
                    $parent = $res['refid'];
                }
                $tree[] = ['/forum/', $lng['forum']];
                krsort($tree);
                $tree[] = [$thread_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id, $th1['text']];

                $breadcrumb = new breadcrumb(1);
                $breadcrumb->add($tree);
                $_breadcrumb = $breadcrumb->out();
                if ($allow == 4) {
                    $_error = 'Bạn không có quyền trả lời trong chuyên mục này!';
                } elseif ($th1['thread_closed'] == 1 && $rights < RIGHTS_ADMIN) {
                    $_error = $lng['error_topic_closed'];
                    $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                } elseif ($type1['user_id'] == $user_id) {
                    $_error = 'Bạn không thể trích dẫn bài viết của chính mình!';
                    $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                } else {
                    // Check for flood
                    $flood = functions::antiflood();
                    if ($flood) {
                        $_error = $lng['error_flood'] . ' ' . $flood . $lng['sec'];
                        $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                    }
                }
            } else {
                $_error = $lng['error_topic_deleted'];
                $tpl_data['back_url'] = SITE_URL . '/forum/';
            }
        } else {
            $_error = $lng['error_post_deleted'];
            $tpl_data['back_url'] = SITE_URL . '/forum/';
        }
        if (!$_error) {
            $msg = isset($_POST['msg']) ? functions::checkin($_POST['msg']) : '';
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
            $input_message = $msg;
            $citata = isset($_POST['citata']) ? functions::checkin($_POST['citata']) : '';
            if ($msg && $citata) {
                // If you have a quote, format it, and treat
                $citata = preg_replace('#\[quote\](.*?)\[/quote\]#si', '', $citata);
                $citata = preg_replace('#\[quote=([^\]]+)\](.*?)\[/quote\]#si', '', $citata);
                $msg = '[quote=' . $type1['id'] . ',' . $type1['user_id'] . ',' . $type1['from'] . ']' . $citata . '[/quote]' . "\n" . $msg;
            }
            $error = false;
            if (isset($_POST['submit']) && TOKEN_VALID) {
                // Check the minimum length
                if (mb_strlen($input_message) < MIN_FORUM_MESSAGE_LENGTH) {
                    $error = $lng['error_message_short'];
                } else {
                    // Handle links
                    $msg = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
                    // Check, if the message is not repeated?
                    $req = mysql_query('SELECT `text` FROM `phonho_posts` WHERE `user_id` = "' . $user_id . '" ORDER BY `time` DESC LIMIT 1');
                    if (mysql_num_rows($req) > 0) {
                        $res = mysql_fetch_array($req);
                        if ($msg == $res['text']) {
                            $error = $lng['error_message_exists'];
                        }
                    }
                }
                if (!$error) {
                    // Add a message on the base
                    mysql_query('INSERT INTO `phonho_posts` SET
                        `refid` = "' . $type1['refid'] . '",
                        `time` = "' . SYSTEM_TIME . '",
                        `user_id` = "' . $user_id . '",
                        `from` = "' . $login . '",
                        `ip` = "' . core::$ip . '",
                        `ip_via_proxy` = "' . core::$ip_via_proxy . '",
                        `soft` = "' . mysql_real_escape_string($agn1) . '",
                        `text` = "' . mysql_real_escape_string($msg) . '"
                    ');
                    $fadd = mysql_insert_id();
                    // Обновляем время топика
                    mysql_query('UPDATE `phonho_threads` SET `time` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $type1['refid'] . '"');
                    // Update user statistics
                    mysql_query('UPDATE `users` SET `postforum` = "' . ($datauser['postforum'] + 1) . '", `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
                    // Compute, which page gets added post
                    $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $type1['refid'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0) / $kmess);
                    if (isset($_POST['addfiles'])) {
                        header('Location: ' . SITE_URL . '/forum/posts/' . $fadd . '/addfile');
                    } else {
                        header('Location: ' . $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $fadd);
                    }
                    exit;
                }
            }

            $page_title = 'Trích dẫn';
            require(ROOTPATH . 'system/header.php');
            $tpl_file = 'forum::posts.quote';
            $tpl_data['error'] = ($error ? functions::display_error($error) : '');
            $show_rules = false;
            if ($datauser['postforum'] == 0) {
                if (!isset($_GET['yes'])) {
                    $lng = array_merge($lng, core::load_lng('faq'));
                    $show_rules = true;
                    $tpl_data['agree_url'] = SITE_URL . '/forum/posts/' . $id . '/quote?yes';
                    $tpl_data['deny_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                }
            }
            $tpl_data['show_rules'] = $show_rules;
            $tpl_data['position'] = $position;
            $tpl_data['post_author'] = $type1['from'];
            if (!$show_rules) {
                if (empty($citata)) {
                    $citata = preg_replace('#\[quote\](.*?)\[/quote\]#si', '', $type1['text']);
                    $citata = preg_replace('#\[quote=([^\]]+)\](.*?)\[/quote\]#si', '', $citata);
                }
                $tpl_data['preview_mode'] = false;
                if ($msg && isset($_POST['preview'])) {
                    $tpl_data['preview_mode'] = true;
                    // Handle links
                    $msg_pre = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
                    $tpl_data['preview_post'] = functions::checkout($msg_pre, 3, 1, 1);
                }
                $tpl_data['form_action'] = 'quote';
                $tpl_data['input_quote'] = functions::checkout($citata);
                $tpl_data['input_message'] = (empty($input_message) ? '' : functions::checkout($input_message));
                $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'msg');
                $tpl_data['add_image_mode'] = false;
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
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}