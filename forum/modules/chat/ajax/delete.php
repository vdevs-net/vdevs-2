<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (IS_AJAX) {
    header('Content-Type: application/json; Charset=UTF-8');
    if ($user_id) {
        $query = mysql_query('SELECT `cms_chat`.`uid`, `users`.`rights` FROM `cms_chat` LEFT JOIN `users` ON `users`.`id`=`cms_chat`.`uid` WHERE `cms_chat`.`id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($query)) {
            $res = mysql_fetch_assoc($query);
            if ($rights >= 6 && ($rights > $res['rights'] || $user_id == $res['uid'])) {
                mysql_query('DELETE FROM `cms_chat` WHERE `id` =  "' . $id . '" LIMIT 1');
            } else {
                $ajax_data['success'] = false;
                $ajax_data['status'] = 403;
                $ajax_data['message'] = 'Bạn không có quyền xóa tin nhắn này';
            }
        } else {
            $ajax_data['success'] = false;
            $ajax_data['status'] = 404;
            $ajax_data['message'] = 'Tin nhắn không tồn tại';
        }
    } else {
        $ajax_data['success'] = false;
        $ajax_data['status'] = 403;
        $ajax_data['message'] = $lng['access_guest_forbidden'];
    }
    die(json_encode($ajax_data));
}