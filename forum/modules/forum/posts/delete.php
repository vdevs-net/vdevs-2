<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
if ($id) {
    if ((!$user_id || isset($ban['1']) || isset($ban['11']) || $set['mod_forum'] == 3) && $rights < RIGHTS_ADMIN) {
        $error = $lng['access_forbidden'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
    } else {
        $req = mysql_query('SELECT `refid`, `user_id`, `time`, `text`, `post_deleted` FROM `phonho_posts` WHERE `id` = "' . $id . '"' . ($rights == 9 ? '' : ' AND `post_deleted` != "1"') . 'LIMIT 1');
        if (mysql_num_rows($req)) {
            // Preliminary checks
            $res = mysql_fetch_assoc($req);
            $thread_req = mysql_query('SELECT `thread_closed`, `text`, `refid`, `user_id`, `first_post_id` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '"' . ($rights > RIGHTS_ADMIN ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
            if (mysql_num_rows($thread_req)) {
                $topic = mysql_fetch_assoc($thread_req);
                $position = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <= "' . $id . '"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0);
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
                $tree[] = [$thread_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id, $topic['text']];

                $breadcrumb = new breadcrumb(1);
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
                            $error = 'Bạn không có quyền xóa bài viết này!';
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
        if (IS_POST && TOKEN_VALID) {
            // Removing the post and attached file
            if ($rights == 9 && isset($_POST['del'])) {
                // Deleting a post (Supervisor)
                $forum = new forum();
                if ($id == $topic['first_post_id']) {
                    // go to delete topic
                    $forum->del_topic($res['refid']);
                    mysql_query('DELETE FROM `phonho_threads` WHERE `id`="' . $res['refid'] . '"');
                    $cat_name = mysql_result(mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `type`="r" AND `id` = "' . $topic['refid'] . '" LIMIT 1'), 0);
                    header('Location: ' . SITE_URL . '/forum/forums/' . functions::makeUrl($cat_name) . '.' . $topic['refid'] . '/'); exit;
                } else {
                    // delete post
                    $forum->del_post($id);
                    $_page = ceil(($position - 1) / $kmess);
                    mysql_query('DELETE FROM `phonho_posts` WHERE `id` = "' . $id . '"');
                }
            } else {
                // Closing post
                // hide it attached file
                mysql_query('UPDATE `cms_forum_files` SET `del` = "1" WHERE `post` = "' . $id . '" LIMIT 1');
                if ($id == $topic['first_post_id']) {
                    // If this was the last post topics, then hide itself subject
                    mysql_query('UPDATE `phonho_threads` SET `thread_deleted` = "1", `thread_deleted_user` = "' . $login . '" WHERE `id` = "' . $res['refid'] . '"');
                    mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "1", `post_deleted_user` = "' . $login . '" WHERE `refid` = "' . $res['refid'] . '"');
                    if ($rights < RIGHTS_ADMIN) {
                        header('Location: ' . SITE_URL . '/forum/'); exit;
                    }
                } else {
                    mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "1", `post_deleted_user` = "' . $login . '" WHERE `id` = "' . $id . '"');
                }
                if ($rights < RIGHTS_ADMIN) {
                    $_page = ceil(($position - 1) / $kmess);
                }
            }
            header('Location: ' . $thread_abs_url . 'page-' . $_page); exit;
        } else {
            $tpl_file = 'page.confirm';
            $tpl_data['form_action'] = 'delete';
            $tpl_data['confirm_text'] = 'Bạn có thực sự muốn xóa bài viết #' . $position;
            $tpl_data['cancel_url'] = $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post'. $id;
            if ($id == $topic['first_post_id']) {
                $tpl_data['confirm_warning'] = $lng['delete_first_post_warning'];
            }
            if ($rights == RIGHTS_SUPER_ADMIN) {
                $tpl_data['confirm_options'] = [
                    [
                        'title' => 'Xóa vĩnh viến',
                        'items' => [
                            [
                                'type' => 'radio',
                                'name' => 'del',
                                'value' => 1,
                                'explain' => 'Bài viết không thể khôi phục nếu bạn chọn lựa chọn này!'
                            ]
                        ]
                    ]
                ];
            }
        }
    } else {
        // Displays an error message
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = functions::display_error($error);
        $tpl_data['back_text'] = $lng['back'];
    }
}