<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'chatroom';
$page_title = 'Chat room';

$breadcrumb = new breadcrumb();
$breadcrumb->add('Chat room');
$_breadcrumb = $breadcrumb->out();

if ($user_id) {
	$tpl_file = 'chat::main';
	$text = '';
	$error = '';
	if (IS_POST && TOKEN_VALID) {
		$text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
		$flood = functions::antiflood();
		if (isset($ban['1']) || isset($ban['12'])){
			$error = 'Bạn đang bị cấm chat!';
		} elseif($flood){
			$error = 'Bạn không được gửi tin nhắn quá nhanh! Vui lòng chờ ' . $flood . ' giây!';
		} elseif(empty($text) || mb_strlen($text) < 2 || mb_strlen($text) > 1023){
			$error = 'Độ dài tin nhắn là từ 2 đến 1023 ký tự!';
		}
		if (empty($error)) {
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
				}
			}
			mysql_query('UPDATE `cms_settings` SET `val` = "' . SYSTEM_TIME . '" WHERE `key` = "chat_last"');
			mysql_query('UPDATE `users` SET `coin` = "' . $datauser['coin']  . '", `daily_reward_received` = "' . date('Y-m-d') . '", `lastpost`="' . SYSTEM_TIME . '", `lastdate` = "' . SYSTEM_TIME . '" WHERE `id`="' . $user_id . '"');
			header('Location: ' . SITE_URL . '/chat/?r=' . rand(1000,9999)); exit;
		}
	}
	require(ROOTPATH . 'system/header.php');
	if ($datauser['chat_read'] < $set['chat_last']) {
		$datauser['chat_read'] = SYSTEM_TIME;
		mysql_query('UPDATE `users` SET `chat_read` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
	}
	$chat_count = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_chat`'), 0);
	$tpl_data['total'] = $chat_count;
	$tpl_data['pagination'] = ($chat_count > $kmess ? functions::display_pagination('page-', $start, $chat_count, $kmess) : '');
	$tpl_data['error'] = ($error ? functions::display_error($error) : '');
	$tpl_data['form_action'] = SITE_URL . '/chat/';
	$tpl_data['bbcode_editor'] = bbcode::auto_bb('chat','chat_input');
	$req = mysql_query('SELECT `cms_chat`.*, `users`.`account`, `users`.`rights` FROM `cms_chat` LEFT JOIN `users` ON `users`.`id`=`cms_chat`.`uid` ORDER BY `cms_chat`.`id` DESC LIMIT ' . ($allow_chat_pagination ? $start . ', ' : '') . $kmess);
	$tpl_data['messages'] = [];
    while ($res = mysql_fetch_assoc($req)) {
        $can_delete = false;
        if ($rights >= 6 && ($rights > $res['rights'] || $user_id == $res['uid'])) {
            $can_delete = true;
        }
		$tpl_data['messages'][] = [
            'data_id'          => ($can_delete ? $res['id'] : 0),
			'user_name'        => $res['account'],
			'user_profile_url' => SITE_URL . '/profile/' . $res['account'] . '.'.$res['uid'] . '/',
			'user_html_class'  => 'user_' . $res['rights'],
			'time'             => functions::display_date($res['time']),
			'data_time'        => $res['time'],
			'text'             => functions::checkout($res['text'], 1, 1, 1)
		];
	}
} else {
	$tpl_file = 'page.error';
	$tpl_data['page_content'] = $lng['access_forbidden'];
}
