<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/messages/', $lng['mail']);
$breadcrumb->add('Soạn tin nhắn');
$_breadcrumb = $breadcrumb->out();

$error = [];
if ($id) {
    if ($id != $user_id) {
        $req = mysql_query('SELECT `id`, `account` FROM `users` WHERE `id` = "' . $id . '" AND `preg` = "1" LIMIT 1');
        if (mysql_num_rows($req)) {
            $qs = mysql_fetch_assoc($req);
            $messages_url = SITE_URL . '/messages/' . $qs['account'] . '.' . $id . '/';
        } else {
            $error[] = $lng['error_user_not_exist'];
        }
    } else {
        $error[] = $lng['impossible_add_message'];
    }
}
if (!$error) {
    if (!$id) {
        $name = isset($_POST['nick']) ? functions::checkin($_POST['nick']) : '';
    }
    $text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
    if (IS_POST && empty($ban['1']) && empty($ban['3'])) {
        $newfile = '';
        $sizefile = 0;
        $do_file = FALSE;

        if (!$id && empty($name)) {
            $error[] = $lng['indicate_login_grantee'];
        }
        if (empty($text)) {
            $error[] = $lng['message_not_empty'];
        } elseif (mb_strlen($text) < 2 || mb_strlen($text) > 5000) {
            $error[] = $lng['error_long_message'];
        }
        if (($id && $id == $user_id) || (!$id && mb_strtolower($datauser['account']) == mb_strtolower($name))) {
            $error[] = $lng['impossible_add_message'];
        }
        $flood = functions::antiflood();
        if ($flood) {
            $error[] = $lng['error_flood'] . ' ' . $flood . ' ' . $lng['sec'];
        }
        if (!$error && !$id) {
            $query = mysql_query('SELECT `id`, `account` FROM `users` WHERE `account`="' . mysql_real_escape_string($name) . '" LIMIT 1');
            if (mysql_num_rows($query)) {
                $qs = mysql_fetch_assoc($query);
                $id = $qs['id'];
                $messages_url = SITE_URL . '/messages/' . $qs['account'] . '.' . $id . '/';
            } else {
                $error[] = $lng['error_user_not_exist'];
            }
        }

        if (!$error) {
            $info = array();
            reset($_FILES);
            $temp = current($_FILES);
            if (is_uploaded_file($temp['tmp_name'])) {
                $do_file = TRUE;
                $fname = $temp['name'];
                $fsize = $temp['size'];
                if (!empty($temp['error'])) {
                    $error[] = $lng['error_load_file'];
                }

            }

            if (!$error && $do_file) {
                // Windows
                // $ext_win = array('exe', 'msi');
                $ext_win = array();
                // Java
                //$ext_java = array('jar', 'jad');
                $ext_java = array();
                // SIS
                //$ext_sis = array('sis', 'sisx', 'apk');
                $ext_sis = array();
                // text
                $ext_doc = array('txt', 'pdf', 'doc', 'docx', 'rtf', 'djvu', 'xls', 'xlsx');
                // picture
                $ext_pic = array('jpg','jpeg','png','bmp','wmf');
                // archive
                $ext_zip = array('zip','rar','7z','tar','gz');
                // video
                //$ext_video = array('3gp','avi','flv','mpeg','mp4');
                $ext_video = array();
                // audio
                //$ext_audio = array('mp3', 'amr');
                $ext_audio = array();
                $ext = array_merge($ext_win, $ext_java, $ext_sis, $ext_doc, $ext_pic, $ext_zip, $ext_video, $ext_audio);
                $info = parseFileName($fname);
                if (empty($info['filename'])) {
                    $error[] = $lng['error_empty_name_file'];
                }
                if (empty($info['fileext'])) {
                    $error[] = $lng['error_empty_ext_file'];
                }
                if ($fsize > (1024 * $set['flsz'])) {
                    $error[] = $lng['error_max_file_size'];
                }
                if (preg_match('/[^a-z0-9\.\(\)\+\_\-]/', $info['filename'])) {
                    $error[] = $lng['error_simbol'];
                }
                if (!in_array($info['fileext'], $ext)) {
                    $error[] = $lng['error_ext_type'] . ': ' . implode(', ', $ext);
                }
                $newfile = $info['filename'] . '.' . $info['fileext'];
                $sizefile = $fsize;

                if (!$error) {
                    // Checking a file with the same name
                    if (file_exists(ROOTPATH . 'files/messages/' . $newfile) !== FALSE) {
                        $newfile = SYSTEM_TIME . '_' . $newfile;
                    }
                }
                if (!$error) {
                    if ((move_uploaded_file($temp['tmp_name'], ROOTPATH . 'files/messages/' . $newfile)) === TRUE) {
                        @chmod(ROOTPATH . 'files/messages/' . $newfile, 0666);
                        @unlink($temp['tmp_name']);
                    } else {
                        $error[] = $lng['error_load_file'];
                    }
                }
            }

            // Check to repeat Posts
            if (!$error) {
                $rq = mysql_query('SELECT `text` FROM `cms_mail` WHERE `user_id` = "' . $user_id . '" AND `from_id` = "' . $id . '" ORDER BY `id` DESC LIMIT 1');
                $rres = mysql_fetch_assoc($rq);
                if ($rres['text'] == $text) {
                    $error[] = $lng['error_message_exists'];
                }
            }


            if (!$error) {
                mysql_query("INSERT INTO `cms_mail` SET
        		`user_id` = '" . $user_id . "',
        		`from_id` = '" . $id . "',
        		`text` = '" . mysql_real_escape_string($text) . "',
        		`time` = '" . SYSTEM_TIME . "',
        		`file_name` = '" . mysql_real_escape_string($newfile) . "',
        		`size` = '" . $sizefile . "'");

                mysql_query("UPDATE `users` SET `lastpost` = '" . SYSTEM_TIME . "' WHERE `id` = '$user_id';");
                header('Location: ' . $messages_url); exit;
            }
        }
    }
    $tpl_file = 'messages::write';
    $tpl_data['can_write'] = false;
    if (empty($ban['1']) && empty($ban['3'])) {
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['can_write'] = true;
        $tpl_data['form_action'] = 'write' . ($id ? '?id=' . $id : '');
        if ($id) {
            $tpl_data['require_name'] = false;
            $tpl_data['user_name'] = $qs['account'];
            $tpl_data['user_profile_url'] = SITE_URL . '/profile/' . $qs['account'] . '.' . $id . '/';
        } else {
            $tpl_data['require_name'] = true;
            $tpl_data['user_name'] = ($name ? functions::checkout($name) : '');
        }
        $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'text');
        $tpl_data['input_message'] = ($text ? functions::checkout($text) : '');
    }

    $page_title = $lng['mail'];
    require_once(ROOTPATH . 'system/header.php');

} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = functions::display_error($error);
}