<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/messages/', $lng['mail']);
$breadcrumb->add($lng['systems_messages']);
$_breadcrumb = $breadcrumb->out();

if ($mod == 'clear') {
    if (IS_POST && TOKEN_VALID) {
        $count_message = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `from_id`='$user_id' AND `sys`='1';"), 0);
        if ($count_message) {
            $req = mysql_query("SELECT `id` FROM `cms_mail` WHERE `from_id`='$user_id' AND `sys`='1' LIMIT " . $count_message);
            $mass_del = array();
            while ($row = mysql_fetch_assoc($req)) {
                $mass_del[] = $row['id'];
            }
            if ($mass_del) {
                $result = implode(',', $mass_del);
                mysql_query("DELETE FROM `cms_mail` WHERE `id` IN (" . $result . ")");
            }
        }
        $tpl_file = 'page.success';
        $tpl_data['page_content'] = $lng['messages_are_removed'];
        $tpl_data['back_url'] = SITE_URL . '/messages/';

    } else {
        $tpl_file = 'page.confirm';
        $tpl_data['form_action'] = 'systems?mod=clear';
        $tpl_data['confirm_text'] = $lng['really_messages_removed'];
        $tpl_data['cancel_url'] = 'systems';
    }
} else {
    $tpl_file = 'messages::systems';
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `from_id`='$user_id' AND `sys`='1' AND `delete`!='$user_id';"), 0);
    $tpl_data['total'] = $total;
    $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('systems?page=', $start, $total, $kmess) : '');
    $tpl_data['items'] = [];
    if ($total) {
        $req = mysql_query("SELECT * FROM `cms_mail` WHERE `from_id`='$user_id' AND `sys`='1' AND `delete`!='$user_id' ORDER BY `time` DESC LIMIT " . $start . "," . $kmess);
        $mass_read = array();
        while ($row = mysql_fetch_assoc($req)) {
            if ($row['read'] == 0 && $row['from_id'] == $user_id) {
                $mass_read[] = $row['id'];
            }
            $tpl_data['items'][] = [
                'title'      => functions::checkout($row['them']),
                'time'       => functions::display_date($row['time']),
                'message'    => functions::checkout($row['text'], 1, 1, 1),
                'delete_url' => SITE_URL . '/messages/' . $row['id'] . '/delete'
            ];
        }
        // Put a mark on the reading
        if ($mass_read) {
            mysql_query('UPDATE `cms_mail` SET `read`="1" WHERE `from_id`="' . $user_id . '" AND `sys`="1" AND `id` IN (' . implode(', ', $mass_read) . ')');
        }
    }
}
$page_title = $lng['systems_messages'];
require_once(ROOTPATH . 'system/header.php');
