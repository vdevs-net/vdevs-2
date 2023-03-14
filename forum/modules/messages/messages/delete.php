<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require_once(ROOTPATH . 'system/header.php');
$breadcrumb = new breadcrumb();
$breadcrumb->add('/messages/', $lng['mail']);
$breadcrumb->add($lng['deleted_messages']);
$_breadcrumb = $breadcrumb->out();

if ($id) {
    $return_uri = isset($_GET['return_uri']) ? functions::checkout(rawurldecode(trim($_GET['return_uri']))) : '/messges/';
    //Checks Posts
    $req = mysql_query("SELECT * FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `id` = '$id' AND `delete`!='$user_id' LIMIT 1;");
    if (mysql_num_rows($req)) {
        $res = mysql_fetch_assoc($req);
        if (IS_POST && TOKEN_VALID) {
            // Remove the system message
            if ($res['sys']) {
                mysql_query("DELETE FROM `cms_mail` WHERE `from_id`='$user_id' AND `id` = '$id' AND `sys`='1' LIMIT 1");
                $tpl_data['back_url'] = SITE_URL . '/messages/systems';
            } else {
                // Remove unread message
                if ($res['read'] == 0 && $res['user_id'] == $user_id) {
                    // delete files
                    if ($res['file_name']) {
                        @unlink(ROOTPATH . 'files/messages/' . $res['file_name']);
                    }
                    mysql_query("DELETE FROM `cms_mail` WHERE `user_id`='$user_id' AND `id` = '$id' LIMIT 1");
                } else {
                    // Remove the remaining messages
                    if ($res['delete']) {
                        // delete files
                        if ($res['file_name']) {
                            @unlink(ROOTPATH . 'files/messages/' . $res['file_name']);
                        }
                        mysql_query("DELETE FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `id` = '$id' LIMIT 1");
                    } else {
                        mysql_query("UPDATE `cms_mail` SET `delete` = '$user_id' WHERE `id` = '$id' LIMIT 1");
                    }
                }
                $tpl_data['back_url'] = SITE_URL . $return_uri;
            }
            $tpl_file = 'page.success';
            $tpl_data['page_content'] = $lng['messages_delete_ok'];
            $tpl_data['back_text'] = $lng['back'];
        } else {
            $tpl_file = 'page.confirm';
            $tpl_data['form_action'] = 'delete?id=' . $id . '&return_uri=' . urlencode($return_uri);
            $tpl_data['confirm_text'] = $lng['really_delete_message'];
            $tpl_data['cancel_url'] = SITE_URL . $return_uri;
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['messages_does_not_exist'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['not_message_is_chose'];
}