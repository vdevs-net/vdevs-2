<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
if ($id) {
    if ((!$user_id || isset($ban['1']) || isset($ban['11']) || $set['mod_forum'] == 3) && $rights < RIGHTS_ADMIN) {
        $error = $lng['access_forbidden'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
    } else {
        $req = mysql_query('SELECT `refid`, `user_id`, `time`, `text`, `post_deleted` FROM `phonho_posts` WHERE `id` = "' . $id . '" AND `post_deleted` = "0" LIMIT 1');
        if (mysql_num_rows($req)) {
            // Preliminary checks
            $res = mysql_fetch_assoc($req);
            $thread_req = mysql_query('SELECT `thread_closed`, `text`, `refid`, `user_id`, `first_post_id` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '"' . ($rights > RIGHTS_ADMIN ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
            if (mysql_num_rows($thread_req)) {
                $topic = mysql_fetch_assoc($thread_req);
                $position = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id`<="' . $id . '"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0);
                $_page = ceil($position / $kmess);
                $thread_url = '/forum/threads/' . functions::makeUrl($topic['text']) . '.' . $res['refid'] . '/';
                $thread_abs_url = SITE_URL . $thread_url;
                // The resulting structure Forum
                $br_res = true;
                $allow = 0;
                $parent = (int) $topic['refid'];
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
                $tree[] = [$thread_url, $topic['text']];

                $breadcrumb = new breadcrumb(0, 1);
                $breadcrumb->add($tree);
                $_breadcrumb = $breadcrumb->out();

                $error = FALSE;
                if ($rights == 3 || $rights >= 6) {
                    // Check for Administration
                    if ($res['user_id'] != $user_id) {
                        $req_u = mysql_query('SELECT `rights` FROM `users` WHERE `id` = "' . $res['user_id'] . '" LIMIT 1');
                        if (mysql_num_rows($req_u)) {
                            $res_u = mysql_fetch_assoc($req_u);
                            if ($res_u['rights'] > $datauser['rights']) {
                                $error = $lng['error_edit_rights'];
                                $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                            }
                        }
                    }
                } else {
                    // Check for normal users
                    if ($res['user_id'] != $user_id) {
                        $error = $lng['error_edit_another'];
                        $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                    }
                    if (!$error) {
                        $check = TRUE;
                        if ($allow == 4 && $rights < RIGHTS_ADMIN) {
                            $error = 'Bạn không có quyền chỉnh sửa bài viết này!';
                            $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                        } else {
                            if ($allow == 2) {
                                if ($topic['user_id'] == $res['user_id'] && $topic['first_post_id'] == $id) {
                                    $check = FALSE;
                                }
                            }

                            if ($check) {
                                $req_m = mysql_query('SELECT `id`, `user_id` FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" ORDER BY `id` DESC LIMIT 1');
                                $res_m = mysql_fetch_assoc($req_m);
                                if ($res_m['id'] != $id || $res_m['user_id'] != $user_id) {
                                    $error = $lng['error_edit_last'];
                                    $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                                } elseif ($res['time'] < SYSTEM_TIME - 300) {
                                    $error = $lng['error_edit_timeout'];
                                    $tpl_data['back_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id;
                                }
                            }
                        }
                    }
                }
            } else {
                $error = $lng['error_topic_deleted'];
                $tpl_data['back_url'] = SITE_URL . '/forum/';
            }
        } else {
            $error = $lng['error_post_deleted'];
            $tpl_data['back_url'] = SITE_URL . '/forum/';
        }
    }
    if (!$error) {
        // Edit post
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
        $error = false;
        if (isset($_POST['submit']) && TOKEN_VALID) {
            if (mb_strlen($msg) < MIN_FORUM_MESSAGE_LENGTH) {
                $error = $lng['error_empty_message'];
            } else {
                // Handle links
                $msg = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
                mysql_query('UPDATE `phonho_posts` SET
                    `tedit` = "' . SYSTEM_TIME . '",
                     `edit` = "' . $login . '",
                    `text` = "' . mysql_real_escape_string($msg) . '"
                    WHERE `id` = "' . $id . '"
                ');
                header('Location: ' . $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id); exit;
            }
        }
        $tpl_file = 'forum::posts.edit';
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['preview_mode'] = false;
        if ($msg && isset($_POST['preview'])) {
            $tpl_data['preview_mode'] = true;
            // Handle links
            $msg_pre = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $msg);
            $tpl_data['preview_post'] = functions::checkout($msg_pre, 3, 1, 1);
        }
        $tpl_data['position'] = $position;
        $tpl_data['form_action'] = 'edit';
        $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'msg');
        $tpl_data['input_message'] = (empty($msg) ? functions::checkout($res['text']) : functions::checkout($msg));
        $tpl_data['add_image_mode'] = false;
        if (isset($_POST['add_image'])) {
            $tpl_data['add_image_mode'] = true;
            $tpl_data['recent_images'] = functions::get_recent_images();
        }
    } else {
        // Displays an error message
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = functions::display_error($error);
        $tpl_data['back_text'] = $lng['back'];
    }
}