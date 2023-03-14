<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Lịch sử giao dịch';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/shop/', 'Shop');

$nick = false;
if ($user && $user != $user_id && $rights == 9) {
	$req = mysql_query('SELECT `account` FROM `users` WHERE `id`="'.$user.'" LIMIT 1');
	if(mysql_num_rows($req)){
		$nick = mysql_result($req, 0);
		$uid = $user;
	} else {
		$tpl_file = 'page.error';
		$tpl_data['page_content'] = $lng['error_user_not_exist'];
	}
} else {
	$uid = $user_id;
}
$breadcrumb->add('Lịch sử giao dịch' . ($nick ? (' của <a href="' . SITE_URL . '/profile/' . $nick . '.' . $user . '/"><b>' . $nick . '</b></a>') : ''));
if (!$tpl_file) {

	$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_log` WHERE `uid`="' . $uid . '"'), 0);
	$tpl_file = 'shop::history';
	$tpl_data['total'] = $total;
	$tpl_data['items'] = array();
	$tpl_data['pagination'] = '';
	if ($total) {
		$types = array(
			2 => 'Chuyển xu',
			3 => 'Nhận xu',
			4 => 'Tạo logo',
			5 => 'Mua icon',
            7 => 'Chuyển đổi',
            8 => 'Tham gia chat mỗi ngày'
		);
		$req = mysql_query('SELECT `cms_log`.*, `users`.`id`, `users`.`account` FROM `cms_log` LEFT JOIN `users` ON `users`.`id` = `cms_log`.`pid` WHERE `uid` = "' . $uid . '" ORDER BY `time` DESC LIMIT ' . $start . ',' . $kmess);
		while ($res = mysql_fetch_assoc($req)) {
			$item = $types[$res['type']];
			if ($res['type'] == 1) {
				$item .= ' mệnh giá ' . $res['text'] . '';
			} elseif($res['type'] == 2) {
				$item .= ' cho <a href="' . SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/">'.$res['account'].'</a> số lượng '.$res['text'].'xu, mất thêm 10% phí';
			} elseif($res['type'] == 3) {
				$item .= ' từ <a href="' . SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/">'.$res['account'].'</a> số lượng '.$res['text'].'xu';
			} elseif($res['type'] == 6) {
				$price = array(
					1 => 2000,
					2 => 5000,
					3 => 10000,
					4 => 20000,
					5 => 50000
				);
				$time = array(
					1 => 30,
					2 => 90,
					3 => 180,
					4 => 365,
					5 => 3650
				);
				$item .= ' gói '.$res['text'].' có thời hạn '. $time[$res['text']] .' ngày với chi phí '.$price[$res['text']] .' Gold';
			} elseif ($res['type'] == 7) {
                $item .= ' ' . $res['text'] . ' Gold thành ' . (BUY_COIN_RATIO * $res['text']) . ' xu';
			} elseif ($res['type'] == 8) {
                $item .= ' nhận '.$res['text'].' xu';
            } else {
				$item .= ' trả cho <a href="' . SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/">'.$res['account'].'</a> số lượng '.$res['text'].'xu';
			}
			$item .= ' ('.functions::display_date($res['time']).')';
			$tpl_data['items'][] = $item;
		}
		if ($total > $kmess) {
			$tpl_data['pagination'] = functions::display_pagination('history?' . ($user ? 'user=' . $user . '&' : '') . 'page=', $start, $total, $kmess);
		}
	}
}

$_breadcrumb = $breadcrumb->out();
