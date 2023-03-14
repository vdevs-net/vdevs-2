<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (IS_AJAX) {
    header('Content-Type: application/json; Charset=UTF-8');
    if ($user_id) {
        if (mb_strtolower(trim($_SERVER['REQUEST_METHOD'])) === 'post' && TOKEN_VALID) {
            $error = false;
            $text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
            $flood = functions::antiflood();
            if (isset($ban['1']) || isset($ban['12'])){
                $error = 'Bạn đang bị cấm chat!';
            } elseif($flood){
                $error = 'Bạn không được gửi tin nhắn quá nhanh! Vui lòng chờ ' . $flood . ' giây!';
            } elseif(empty($text) || mb_strlen($text) < 2 || mb_strlen($text) > 1023){
                $error = 'Độ dài tin nhắn là từ 2 đến 1023 ký tự!';
            }
            if (!$error) {
                if ($text == '/clear' && $rights >= 6) {
                    mysql_query('TRUNCATE `cms_chat`');
                    mysql_query('INSERT INTO `cms_chat` SET `uid` = "2" , `text` = "đã làm sạch chatbox", `time` = "' . SYSTEM_TIME . '"');
                } else {
                    if (strpos($text, '/s ') === 0) {
                        $s = trim(substr($text, 3));
                        if (mb_strlen($s) < 4 || mb_strlen($s) > 64) {
                            $bt = 'Độ dài từ khóa không hợp lệ';
                        } else {
                            $req_s = mysql_query('SELECT `id`, `text`, MATCH (`text`) AGAINST ("' . mysql_real_escape_string($s) . '" IN BOOLEAN MODE) as `rel`
                                FROM `phonho_threads`
                                WHERE MATCH (`text`) AGAINST ("' . mysql_real_escape_string($s) . '" IN BOOLEAN MODE)' . ($rights >= 7 ? '' : ' AND `thread_deleted` != "1"') . '
                                ORDER BY `rel` DESC
                                LIMIT 2
                            ');
                            $results = array();
                            while ($res = mysql_fetch_assoc($req_s)) {
                                $results[] = '[url=' . SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/]' . $res['text'] . '[/url]';
                            }
                            if ($results) {
                                $bt = 'Các kết quả tìm kiếm:' . "\r\n" . implode("\r\n", $results);
                            } else {
                                $bt = 'Không có kết quả phù hợp!';
                            }
                        }
                        $query = mysql_query('INSERT INTO `cms_chat` SET `uid`="' . $user_id . '", `text`="' . mysql_real_escape_string($text) . '", `time`="' . SYSTEM_TIME . '"');
                        mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="' . mysql_real_escape_string($bt) . '", `time`="' . SYSTEM_TIME . '"');
                    } else {
                        $text = preg_replace_callback('~\\[url=(https?://.+?)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zA-Z\.-]+\.[0-9a-zA-Z]{2,6}[0-9a-zA-Z/\?\.\~&_=/%-:#]*)~', 'functions::forum_link', $text);
                        $query = mysql_query('INSERT INTO `cms_chat` SET `uid`="' . $user_id . '", `text`="' . mysql_real_escape_string($text) . '", `time`="' . SYSTEM_TIME . '"');
                    }

                    if (!$datauser['daily_reward_received'] || $datauser['daily_reward_received'] < date('Y-m-d')) {
                        $datauser['coin'] += DAILY_CHAT_REWARD;

                        mysql_query('INSERT INTO `cms_log` (`type`,`uid`,`time`,`text`) VALUES
                            ("8", "' . $user_id . '", "' . SYSTEM_TIME . '", "' . DAILY_CHAT_REWARD . '")
                        ');
                        $ajax_data['has_reward'] = true;
                    }
                }
                mysql_query('UPDATE `cms_settings` SET `val` = "' . SYSTEM_TIME . '" WHERE `key` = "chat_last"');
                mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin']  . '", `daily_reward_received` = "' . date('Y-m-d') . '", `lastpost`="' . SYSTEM_TIME . '", `lastdate` = "' . SYSTEM_TIME . '" WHERE `id`="' . $user_id . '"');
            } else {
                $ajax_data['success'] = false;
                $ajax_data['message'] = $error;
            }
        } else {
            $ajax_data['success'] = false;
            $ajax_data['message'] = 'Dữ liệu không chính xác. Vui lòng tải lại trang!';
        }
    } else {
        $ajax_data['success'] = false;
        $ajax_data['status'] = 403;
        $ajax_data['message'] = $lng['access_guest_forbidden'];
    }
    die(json_encode($ajax_data));
}
