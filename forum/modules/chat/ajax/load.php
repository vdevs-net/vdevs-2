<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (IS_AJAX) {
    header('Content-Type: application/json; Charset=UTF-8');
    if ($user_id) {
        $get  = isset($_GET['get']) ? trim($_GET['get']) : '';
        $time = isset($_GET['time']) ? abs(intval($_GET['time'])) : 0;
        if ($time == 0) {
            $time = SYSTEM_TIME;
        }
        $compare = ($get == 'before' ? '<' : '>');
        $sql_order = ($compare == '>' ? 'ASC' : 'DESC');

        $query = mysql_query('SELECT `cms_chat`.*, `users`.`account`, `users`.`rights` FROM `cms_chat` LEFT JOIN `users` ON `users`.`id`=`cms_chat`.`uid` WHERE `time` ' . $compare . ' ' . $time . ' ORDER BY `cms_chat`.`id` ' . $sql_order . ' LIMIT ' . $kmess);
        $ajax_data['time'] = SYSTEM_TIME;
        $ajax_data['messages'] = [];
        while ($res = mysql_fetch_assoc($query)) {
            $can_delete = false;
            if ($rights >= 6 && ($rights > $res['rights'] || $user_id == $res['uid'])) {
                $can_delete = true;
            }
            $ajax_data['messages'][] = [
                'data_id'          => ($can_delete ? $res['id'] : 0),
                'user_name'        => $res['account'],
                'user_profile_url' => SITE_URL . '/profile/' . $res['account'] . '.' . $res['uid'] . '/',
                'user_html_class'  => 'user_' . $res['rights'],
                'time'             => functions::display_date($res['time']),
                'data_time'        => $res['time'],
                'text'             => functions::checkout($res['text'], 1, 1, 1)
            ];
        }
        mysql_query('UPDATE `users` SET `chat_read` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
    } else {
        $ajax_data['success'] = false;
        $ajax_data['status'] = 403;
        $ajax_data['message'] = $lng['access_guest_forbidden'];
    }
    die(json_encode($ajax_data));
}