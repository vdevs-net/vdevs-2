<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$db->query('UPDATE `users` SET `lastdate` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user['id'] . '"');

$end_time = SYSTEM_TIME + $mods[$act];
$sleep_time = 3;

$check_time = 0;
if ($time) {
    $check_time = $time;
} else {
    $check_time = SYSTEM_TIME - $sleep_time;
}

while (true)
{
    $new_unread_notification = $db->result($db->query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user['id'] . '" AND `read`="0" AND `sys`="1" AND `delete` != "' . $user['id'] . '" AND `time` > ' . $check_time . ';'), 0);
    // user mail
     $new_unread_message = $db->result($db->query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user['id'] . '" AND `sys` = "0" AND `read` = "0" AND `delete` != "' . $user['id'] . '" AND `time` > ' . $check_time . ';'), 0);

    $new_unread_chat = $db->result($db->query('SELECT COUNT(*) FROM `cms_chat` WHERE `time` > "' . $check_time . '" AND `uid` !="' . $user['id'] . '"'), 0);

    if ($new_unread_notification || $new_unread_message || $new_unread_chat) {
        $ajax_data['status'] = 200;
        $ajax_data['unread_message']          = (int) $db->result($db->query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user['id'] . '" AND `sys` = "0" AND `read` = "0" AND `delete` != "' . $user['id'] . '";'), 0);
        $ajax_data['new_unread_message']      = (int) $new_unread_message;
        $ajax_data['unread_notification']     = (int) $db->result($db->query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user['id'] . '" AND `read`="0" AND `sys`="1" AND `delete` != "' . $user['id'] . '";'), 0);
        $ajax_data['new_unread_notification'] = (int) $new_unread_notification;
        $ajax_data['chat_count']              = (int) $db->result($db->query('SELECT COUNT(*) FROM `cms_chat`'), 0);
        $ajax_data['unread_chat']             = (int) $db->result($db->query('SELECT COUNT(*) FROM `cms_chat` WHERE `time` > "' . $user['chat_read'] . '"'), 0);
        $ajax_data['new_unread_chat']         = (int) $new_unread_chat;
        $ajax_data['time'] = time();
        break;
    } else {
        if ($end_time - time() > $sleep_time) {
            sleep($sleep_time);
        } else {
            break;
        }
    }
}

die(json_encode($ajax_data));
