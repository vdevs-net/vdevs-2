<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($user_id) {
        // Check whether the user fills in the file and whether to place
        $post_req = mysql_query('SELECT `refid`, `time` FROM `phonho_posts` WHERE `id` = "'. $id .'" AND `user_id` = "' . $user_id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `post_deleted` = "0"') . ' LIMIT 1');
        if (mysql_num_rows($post_req)) {
            require(ROOTPATH . 'system/header.php');
            $res = mysql_fetch_assoc($post_req);
            $res2 = mysql_fetch_assoc(mysql_query('SELECT `refid`, `text` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '" LIMIT 1'));
            $position = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <= "' . $id . '"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0);
            $_page = ceil($position / $kmess);
            $thread_url = '/forum/threads/' . functions::makeUrl($res2['text']) . '.' . $res['refid'] . '/';
            $thread_abs_url = SITE_URL . $thread_url;

            $_res = true;
            $_parent = (int) $res2['refid'];
            while ($_parent != 0 && $_res) {
                $_res = mysql_fetch_assoc(mysql_query('SELECT `type`, `allow`, `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $_parent . '" LIMIT 1'));
                if ($_res) {
                    if ($_res['type'] == 'f') {
                        if ($_res['refid'] == 0) {
                            $tree[] = ['/forum/#' . functions::makeUrl($_res['forum_name']) . '-' . $_parent, $_res['forum_name']];
                        } else {
                            $tree[] = ['/forum/categories/' . functions::makeUrl($_res['forum_name']) . '.' . $_parent . '/', $_res['forum_name']];
                        }
                    } else {
                        $tree[] = ['/forum/forums/' . functions::makeUrl($_res['forum_name']) . '.' . $_parent . '/', $_res['forum_name']];
                    }
                }
                $_parent = (int) $_res['refid'];
            }
            $tree[] = ['/forum/', $lng['forum']];
            krsort($tree);

            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($tree);
            $breadcrumb->add($thread_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id, $res2['text']);
            $_breadcrumb = $breadcrumb->out();

            $error = false;
            // Check the time limit allowed for file upload
            if ($res['time'] < (SYSTEM_TIME - 300) && $rights < 7) {
                $error = $lng['upload_timeout'];
            }
            if (!$error) {
                // Check whether the file was already loaded
                if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_files` WHERE `post` = "'. $id .'"'), 0)) {
                    $error = $lng['error_file_uploaded'];
                }
            }
            if (!$error) {
                if (IS_POST) {
                    // Check whether the file is loaded with a browser
                    $error = false;
                    reset($_FILES);
                    $temp = current($_FILES);
                    if (is_uploaded_file($temp['tmp_name'])) {
                        $file = mb_strtolower($temp['name']);
                        $fsize = $temp['size'];
                    } else {
                        $error = 'Không có tập tin nào được chọn!';
                    }
                    // Processing of the file (if any), error checking
                    if (!$error) {
                        // The list of valid file extensions.
                        $al_ext = array_unique(array_merge($ext_win, $ext_java, $ext_sis, $ext_doc, $ext_pic, $ext_arch, $ext_video, $ext_audio, $ext_other));
                        $ext = explode('.', $file);
                        // Check for file size limit
                        if ($fsize > 1024 * $set['flsz']) {
                            $error = $lng['error_file_size'] . ' ' . $set['flsz'] . 'kb.';
                        } elseif (count($ext) > 2) {
                        // Checking the file for the presence of only one extension else
                            $error = $lng['error_file_name'];
                        } else {
                            // Validation of file extensions
                            if (!in_array($ext[1], $al_ext)) {
                                $error = $lng['error_file_ext'] . ':<br />' . implode(', ', $al_ext);
                            } else {
                                // Processing file name
                                if(mb_strlen($ext[0]) == 0) {
                                    $ext[0] = 'NoName';
                                } else {
                                    $ext[0] = str_replace(' ', '_', $ext[0]);
                                }
                                $fname = 'vDevs.Net---' . mb_substr($ext[0], 0, 32) . '.' . $ext[1];
                                // Check for illegal characters
                                if (preg_match('/[^0-9A-Za-z_\-.]/', $fname)) {
                                    $error = $lng['error_file_symbols'];
                                }
                            }
                        }
                        // finishing
                        if (!$error) {
                            // Checking a file with the same name
                            if (file_exists(ROOTPATH . 'files/forum/attach/' . $fname)) {
                                $fname = 'vDevs.Net---' . mb_substr($ext[0], 0, 32) . '--' . SYSTEM_TIME . '.' . $ext[1];
                            }

                            if ((move_uploaded_file($temp['tmp_name'], ROOTPATH . 'files/forum/attach/' . $fname)) == true) {
                                @chmod(ROOTPATH . 'files/forum/attach/' . $fname, 0777);
                                @unlink($temp['tmp_name']);
                                // Determine the type of file
                                if (in_array($ext[1], $ext_win)) $type = 1;
                                elseif (in_array($ext[1], $ext_java)) $type = 2;
                                elseif (in_array($ext[1], $ext_sis)) $type = 3;
                                elseif (in_array($ext[1], $ext_doc)) $type = 4;
                                elseif (in_array($ext[1], $ext_pic)) $type = 5;
                                elseif (in_array($ext[1], $ext_arch)) $type = 6;
                                elseif (in_array($ext[1], $ext_video)) $type = 7;
                                elseif (in_array($ext[1], $ext_audio)) $type = 8;
                                else $type = 9;
                                // Identify the ID and sub-categories
                                $req3 = mysql_query("SELECT `refid` FROM `phonho_forums` WHERE `id` = '" . $res2['refid'] . "' LIMIT 1");
                                $res3 = mysql_fetch_assoc($req3);
                                // Enter data into the database
                                mysql_query('INSERT INTO `cms_forum_files` SET
                                    `cat` = "' . $res3['refid'] . '",
                                    `subcat` = "' . $res2['refid'] . '",
                                    `topic` = "' . $res['refid'] . '",
                                    `post` = "' . $id . '",
                                    `time` = "' . $res['time'] . '",
                                    `filename` = "' . mysql_real_escape_string($fname) . '",
                                    `filetype` = "' . $type . '"
                                ');
                                header('Location: ' . $thread_abs_url . 'page-' . $_page . ($allow_js_scroll ? '?st=' : '#') . 'post' . $id); exit();
                            } else {
                                $error = $lng['error_upload_error'];
                            }
                        }
                    }
                }
                // Form select the file to upload
                $tpl_file = 'forum::posts.addfile';
                $tpl_data['position'] = $position;
                $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                $tpl_data['form_action'] = 'addfile';
                $tpl_data['form_description'] = $lng['max_size'] . ': ' . $set['flsz'] . 'KB';
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $error;
                $tpl_data['back_url'] = $thread_abs_url;
                $tpl_data['back_text'] = $lng['back'];
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_post_deleted'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}
