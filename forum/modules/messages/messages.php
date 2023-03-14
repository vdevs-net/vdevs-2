<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

// An array of connected functions
if ($id) {

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/messages/', $lng['mail']);
    if ($id != $user_id) {
        $req = mysql_query('SELECT `id`, `account` FROM `users` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $qs = mysql_fetch_assoc($req);
            $breadcrumb->add($qs['account']);
            $conversion_url = '/messages/' . $qs['account'] . '.' . $id . '/';
            $conversion_abs_url = SITE_URL . $conversion_url;
            if ($mod == 'clear') {
                if (IS_POST && TOKEN_VALID) {
                    $count_message = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE ((`user_id`='$id' AND `from_id`='$user_id') OR (`user_id`='$user_id' AND `from_id`='$id')) AND `delete`!='$user_id'"), 0);
                    if ($count_message) {
                        $req = mysql_query("SELECT `cms_mail`.* FROM `cms_mail` WHERE ((`cms_mail`.`user_id`='$id' AND `cms_mail`.`from_id`='$user_id') OR (`cms_mail`.`user_id`='$user_id' AND `cms_mail`.`from_id`='$id')) AND `cms_mail`.`delete`!='$user_id' LIMIT " . $count_message);
                        while ($row = mysql_fetch_assoc($req)) {
                            if ($row['delete']) {
                                if ($row['file_name']) {
                                    if (file_exists(ROOTPATH . 'files/messages/' . $row['file_name']) !== FALSE) {
                                        @unlink(ROOTPATH . 'files/messages/' . $row['file_name']);
                                    }
                                }
                                mysql_query("DELETE FROM `cms_mail` WHERE `id`='{$row['id']}' LIMIT 1");
                            } else {
                                if ($row['read'] == 0 && $row['user_id'] == $user_id) {
                                    if ($row['file_name']) {
                                        if (file_exists(ROOTPATH . 'files/messages/' . $row['file_name']) !== FALSE) {
                                            @unlink(ROOTPATH . 'files/messages/' . $row['file_name']);
                                        }
                                    }
                                    mysql_query("DELETE FROM `cms_mail` WHERE `id`='{$row['id']}' LIMIT 1");
                                } else {
                                    mysql_query("UPDATE `cms_mail` SET `delete` = '" . $user_id . "' WHERE `id` = '" . $row['id'] . "' LIMIT 1");
                                }
                            }
                        }
                    }
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['messages_are_removed'];
                    $tpl_data['back_url'] = $conversion_abs_url;
                    $tpl_data['back_text'] = $lng['back'];
                } else {
                    $tpl_file = 'page.confirm';
                    $tpl_data['form_action'] = $conversion_abs_url . '?mod=clear';
                    $tpl_data['confirm_text'] = $lng['really_messages_removed'];
                    $tpl_data['cancel_url'] = $conversion_abs_url;
                }
            } else {
                $tpl_file = 'messages::messages';
                $tpl_data['can_write'] = false;
                if (empty($ban['1']) && empty($ban['3'])) {
                    $tpl_data['can_write'] = true;
                    $tpl_data['form_action'] = SITE_URL . '/messages/write?id=' . $id;
                    $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'text');
                }

                $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE ((`user_id`='$id' AND `from_id`='$user_id') OR (`user_id`='$user_id' AND `from_id`='$id')) AND `sys`!='1' AND `delete`!='$user_id'"), 0);
                $tpl_data['total'] = $total;
                $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('page-', $start, $total, $kmess) : '');
                $tpl_data['items'] = [];
                if ($total) {
                    $req = mysql_query("SELECT `cms_mail`.*, `cms_mail`.`id` as `mid`, `cms_mail`.`time` as `mtime`, `users`.*
                        FROM `cms_mail`
                        LEFT JOIN `users` ON `cms_mail`.`user_id`=`users`.`id`
                        WHERE ((`cms_mail`.`user_id`='$id' AND `cms_mail`.`from_id`='$user_id') OR (`cms_mail`.`user_id`='$user_id' AND `cms_mail`.`from_id`='$id'))
                        AND `cms_mail`.`delete`!='$user_id'
                        AND `cms_mail`.`sys`!='1'
                        ORDER BY `cms_mail`.`time` DESC
                        LIMIT " . $start . "," . $kmess);

                    $mass_read = array();
                    while ($row = mysql_fetch_assoc($req)) {
                        if ($row['read'] == 0 && $row['from_id'] == $user_id) {
                            $mass_read[] = $row['mid'];
                        }
                        $post = $row['text'];
                        $post = functions::checkout($post, 1, 1, 1);
                        if ($row['file_name']) {
                            $post .= '<div class="func">' . $lng['file'] . ': <a href="' . SITE_URL . '/messages/files?fid=' . $row['mid'] . '" class="noPusher">' . $row['file_name'] . '</a> (' . formatsize($row['size']) . ')(' . $row['count'] . ')</div>';
                        }
                        $subtext = '<a href="' . SITE_URL . '/messages/delete?id=' . $row['mid'] . '&return_uri=' . urlencode($conversion_url) . '">' . $lng['delete'] . '</a>';
                        $arg = array(
                            'header'  => '<small>' . functions::display_date($row['mtime']) . '</small>',
                            'body'    => '<div class="text">' . $post . '</div>',
                            'sub'     => $subtext,
                            'stshide' => 1,
                            'iphide'  => 1
                        );
                        // todo: add other style for sender
                        $tpl_data['items'][] = [
                            'html_class' => ($row['read'] ? 'menu' : 'gmenu'),
                            'content'    => functions::display_user($row, $arg)
                        ];
                    }
                    // Put a mark on the reading
                    if ($mass_read) {
                        mysql_query("UPDATE `cms_mail` SET `read`='1' WHERE `from_id`='$user_id' AND `id` IN (" . implode(',', $mass_read) . ")");
                    }
                    $tpl_data['clear_url'] = '?mod=clear';
                }

                $page_title = $lng['mail'];
                require_once(ROOTPATH . 'system/header.php');
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_user_not_exist'];
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_wrong_data'];
    }
    $_breadcrumb = $breadcrumb->out();
} else {
    $tpl_file = 'messages::main';
    $lng = array_merge($lng, core::load_lng('profile'));
    require(ROOTPATH . 'system/header.php');
    $breadcrumb = new breadcrumb();
    $breadcrumb->add($lng['my_mail']);
    $_breadcrumb = $breadcrumb->out();

    $tpl_data['count_input'] = mysql_result(mysql_query("
        SELECT COUNT(*) 
        FROM `cms_mail` 
        WHERE `from_id`='$user_id' 
        AND `sys`='0' AND `delete`!='$user_id'"), 0);

    $tpl_data['count_input_new'] = $unread_message;

    $tpl_data['count_output'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `user_id`='$user_id' AND `delete`!='$user_id' AND `sys`='0'"), 0);

    $tpl_data['count_output_new'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `user_id`='$user_id' AND `delete`!='$user_id' AND `read`='0' AND `sys`='0'"), 0);

    $tpl_data['count_systems'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `from_id`='$user_id' AND `delete`!='$user_id' AND `sys`='1'"), 0);

    $tpl_data['count_systems_new'] = $unread_notification;

    $tpl_data['count_file'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `delete`!='$user_id' AND `file_name`!='';"), 0);
    
    $tpl_data['can_write'] =  (empty($ban['1']) && empty($ban['3']));
}