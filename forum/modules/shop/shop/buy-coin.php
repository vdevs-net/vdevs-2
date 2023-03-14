<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Cửa hàng';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/shop/', 'Shop');
$breadcrumb->add('Mua xu');
$_breadcrumb = $breadcrumb->out();

$error = false;
$inputGold = isset($_POST['gold']) ? abs(intval($_POST['gold'])) : 0;
$tpl_data['success'] = false;
$tpl_data['buyCoinHelp'] = 'Tối thiểu 100 vàng<br />Tỉ lệ chuyển đổi: 100 vàng => ' . (100 * BUY_COIN_RATIO) . ' xu';
if (IS_POST && TOKEN_VALID) {
    $gold = $inputGold * 100;
    $coin = $gold * BUY_COIN_RATIO;
	if ($gold < 100) {
		$error = $lng['error_wrong_data'];
	} elseif ($gold > $datauser['gold']) {
		$error = 'Số dư của bạn không đủ!';
	}
	if (!$error) {
		$datauser['coin'] = $datauser['coin'] + $coin;
        $datauser['gold'] = $datauser['gold'] - $gold;
		mysql_query('UPDATE `users` SET `coin`="' . $datauser['coin'] . '", `gold` = "' . $datauser['gold'] . '" WHERE `id`="' . $user_id . '" LIMIT 1');
		mysql_query('INSERT INTO `cms_log` (`type`,`uid`,`pid`,`time`,`text`) VALUES
			("7", "' . $user_id . '", "2", "' . SYSTEM_TIME . '", "' . $gold . '")
		');
		mysql_query('INSERT INTO `cms_mail` SET `user_id` = "0", `from_id` = "' . $user_id . '", `text` = "Đổi thành công ' . $gold . ' Gold thành ' . $coin . ' xu.", `time` = "' . SYSTEM_TIME . '", `sys` = "1", `them` = "Đổi xu thành công"');
        $tpl_data['success'] = 'Đổi thành công ' . $gold . ' Gold thành ' . $coin . ' xu';
	}
}
$tpl_data['error'] = ($error ? functions::display_error($error) : '');
$tpl_data['form_action'] = 'buy-coin';
$tpl_data['input_gold'] = $inputGold;
$tpl_file = 'shop::buy-coin';
