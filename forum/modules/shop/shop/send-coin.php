<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Cửa hàng';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/shop/', 'Shop');
$breadcrumb->add('Chuyển xu');
$_breadcrumb = $breadcrumb->out();

$error = false;
$tpl_data['success'] = false;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$coin = isset($_POST['coin']) ? abs(intval($_POST['coin'])) : 0;
$tpl_data['sendCoinHelp'] = 'Chuyển tối thiểu 100 xu và là bội số của 10.<br/>Phí chuyển đổi: 10%.<br/>VD: Gửi 100xu -> mất 110 xu, người nhận được 100 xu';
if (IS_POST && TOKEN_VALID) {
	if (!$name || $coin < 100 || $coin%10 != 0) {
		$error = $lng['error_wrong_data'];
	} elseif ($name == $login) {
		$error = 'Bạn không thể gửi xu cho chính mình!';
	} elseif ($coin * 1.1 > $datauser['coin']) {
		$error = 'Số dư của bạn không đủ!';
	}
	if (!$error) {
		$req = mysql_query('SELECT `id`, `coin` FROM `users` WHERE `account`="' . mysql_real_escape_string($name) . '" LIMIT 1');
		if (!mysql_num_rows($req)) {
			$error = $lng['user_does_not_exist'];
		}
	}
	if (!$error) {
		$res = mysql_fetch_assoc($req);
		$cr_new = $res['coin'] + $coin; // new coin value of receiving user
		$datauser['coin'] = $datauser['coin'] - $coin * 1.1; // new coin value of sending user
		mysql_query('UPDATE `users` SET `coin`="' . $cr_new . '" WHERE `id`="' . $res['id'] . '" LIMIT 1');
		mysql_query('UPDATE `users` SET `coin`="' . $datauser['coin'] . '" WHERE `id`="' . $user_id . '" LIMIT 1');
		mysql_query('INSERT INTO `cms_log` (`type`,`uid`,`pid`,`time`,`text`) VALUES
			("2", "' . $user_id . '", "' . $res['id'] . '", "' . SYSTEM_TIME . '", "' . $coin . '"),
			("3", "' . $res['id'] . '", "' . $user_id . '", "' . SYSTEM_TIME . '", "' . $coin . '")
		');
		mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="' . $login . ' vừa chuyển cho ' . $name . ' ' . $coin . ' xu!", `time`="' . SYSTEM_TIME . '"');
		mysql_query('INSERT INTO `cms_mail` SET `user_id` = "0", `from_id` = "' . $res['id'] . '", `text` = "Bạn vừa nhận được ' . $coin . ' xu từ [url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]' . $login . '[/url]", `time` = "' . SYSTEM_TIME . '", `sys` = "1", `them` = "' . $login . ' đã chuyển xu cho bạn"');
		$tpl_data['success'] = 'Chuyển xu thành công cho ' . $name . '. Bạn bị trừ ' . $coin . ' xu và 10% phí giao dịch';
	}
}
$tpl_data['error'] = ($error ? functions::display_error($error) : '');
$tpl_data['form_action'] = 'send-coin';
$tpl_data['input_name'] = functions::checkout($name);
$tpl_data['input_coin'] = $coin;
$tpl_file = 'shop::send-coin';
