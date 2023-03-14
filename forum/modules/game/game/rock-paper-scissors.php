<?php
defined('_MRKEN_CMS') or die('ERROR!');

$page_title = 'Chơi oẳn tù tì';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/game/', 'Game');
$breadcrumb->add('Oẳn tù tì');
$_breadcrumb = $breadcrumb->out();

if (!isset($_SESSION['lt'])) {
	$_SESSION['lt'] = 0;
}
$phi = 50;
$an = 120;
$them = 120;
$tpl_data['error'] = '';
$tpl_data['game_result'] = '';
$error = '';
if (IS_POST && isset($_POST['select']) && TOKEN_VALID) {
	$select = intval($_POST['select']);
	if ($datauser['coin'] < $phi) {
		$error = 'Bạn không đủ xu để chơi vòng này!';
	} else if($select < 1 || $select > 3) {
		$error = $lng['error_wrong_data'];
	} else {
		$flood = functions::antiflood();
		if($flood){
			$error = 'Vui lòng chờ'. $flood . 'giây để chơi tiếp!';
		}
	}
	if (empty($error)) {	
		if(mysql_result(mysql_query('SELECT `coin` FROM `users` WHERE `id`= "2"'), 0) < $an){
			$error = 'Nhà cái phá sản rồi! Vui lòng quay lại sau!';
		}
	}
	if (empty($error)) {
		$mang = array(
			1 => 'Kéo',
			2 => 'Búa',
			3 => 'Bao'
		);
		$bot = mt_rand(1, 3);
		$win = array(
			'Gà quá thím ei! <img src="' . SITE_URL . '/assets/images/smileys/user/other/m4.png"/>',
			'Haha! Lại thắng nữa rồi! <img src="' . SITE_URL . '/assets/images/smileys/user/other/m7.png"/>',
			'Thật là dễ dàng! <img src="' . SITE_URL . '/assets/images/smileys/user/other/win.png"/>',
			'Đừng mơ bắt gà! <img src="' . SITE_URL . '/assets/images/smileys/user/other/yao.png"/>',
			'Tui là BOT, tui có quyền! <img src="' . SITE_URL . '/assets/images/smileys/user/other/gay.png"/>',
			'Cố lên thím! <img src="' . SITE_URL . '/assets/images/smileys/user/other/troll.png"/>',
			'Đừng nản nha! <img src="' . SITE_URL . '/assets/images/smileys/user/other/ngo.png"/>'
		);
		$lose = array(
			'Chỉ là may mắn thôi! <img src="' . SITE_URL . '/assets/images/smileys/user/other/hum.png"/>',
			'Ta mà thua à! <img src="' . SITE_URL . '/assets/images/smileys/user/other/wtf2.png"/>',
			'Lại nữa! <img src="' . SITE_URL . '/assets/images/smileys/user/other/why.png"/>',
			'Đừng chơi hack nha! <img src="' . SITE_URL . '/assets/images/smileys/user/other/huh.png"/>',
			'Nhường thím ván này! <img src="' . SITE_URL . '/assets/images/smileys/user/other/notok.png"/>'
		);
		if ($select == $bot) {
			$_SESSION['lt'] = 0;
			$tpl_data['game_result'] = '<div class="notif">Cả hai cùng chọn '. $mang[$bot] .'! Kết quả hòa!</div>' .
				'<div class="list1"><b>BOT</b>: '. $win[mt_rand(0, count($win) - 1)] .'</div>';
			mysql_query('UPDATE `users` SET `coin` = "'. ($datauser['coin'] - $phi) .'", `lastpost`="'. SYSTEM_TIME .'" WHERE `id` = "'.$user_id.'" LIMIT 1');
			mysql_query('UPDATE `users` SET `coin` = (`coin` + '. $phi .') WHERE `id` = "2" LIMIT 1');
		} elseif (($select == 1 && $bot == 2) || ($select == 2 && $bot == 3) || ($select == 3 && $bot == 1)) {
			$_SESSION['lt'] = 0;
			$tpl_data['game_result'] = '<div class="rmenu">Đối phương chọn '. $mang[$bot] .'! Bạn chọn '. $mang[$select] .'! Bạn thua rồi!</div>' .
				'<div class="list1"><b>BOT</b>: '. $win[mt_rand(0, count($win) - 1)] .'</div>';
			mysql_query('UPDATE `users` SET `coin` = "'. ($datauser['coin'] - $phi) .'", `lastpost`="'. SYSTEM_TIME .'" WHERE `id` = "'.$user_id.'" LIMIT 1');
			mysql_query('UPDATE `users` SET `coin` = (`coin` + '. $phi .') WHERE `id` = "2" LIMIT 1');
		} else {
			$_SESSION['lt']++;
			$coin_plus = 0;
			if($_SESSION['lt'] == 3){
				$coin_plus = $them;
	            mysql_query('INSERT INTO `cms_chat` SET `uid`="2", `text`="Chúc mừng [url=' . SITE_URL . '/profile/' . $datauser['account'] . '.'. $user_id .'/]'. $login .'[/url] đã thắng 3 lần liên tiếp trong [url=' . SITE_URL . '/game/rock-paper-scissors]Oẳn Tù Tì[/url] và nhận '. ($an + $coin_plus) .' xu!", `time`="'. SYSTEM_TIME .'"');
				unset($_SESSION['lt']);
			}
			$cong = $an + $coin_plus - $phi;
			$tpl_data['game_result'] = '<div class="gmenu">Đối phương chọn '. $mang[$bot] .'! Bạn chọn '. $mang[$select] .'! Chúc mừng bạn đã dành chiến thắng! Bạn được cộng '. ($an + $coin_plus) .' xu!</div>' .
				'<div class="list1"><b>BOT</b>: '. $lose[mt_rand(0, count($lose) - 1)] .'</div>';
			mysql_query('UPDATE `users` SET `coin` = "'. ($datauser['coin'] + $cong) .'", `lastpost`="'. SYSTEM_TIME .'" WHERE `id` = "'.$user_id.'" LIMIT 1');
			mysql_query('UPDATE `users` SET `coin` = (`coin` - '. $cong .') WHERE `id` = "2" LIMIT 1');
		}
	} else {
		$tpl_data['error'] = functions::display_error($error);
	}
}
$tpl_data['game_description'] = 'QUY TẮC: mỗi lượt chơi sẽ tốn '. $phi .' xu! Thắng nhận được '. $an .' xu! Thắng 3 lần liên tiếp thưởng thêm '. $them .' xu! Chúc các bạn may mắn!';
$tpl_data['game_source'] = '<form action="' . SITE_URL . '/game/rock-paper-scissors" method="post">' .
	'<table width="100%" class="menu" border="0" cellpadding="0" cellspacing="0"><tr valign="middle"><td width="33%" align="center"><label for="s_1"><img src="' . SITE_URL . '/assets/images/ott/keo.png" max-width="100%"/></label></td><td width="34%" align="center"><label for="s_2"><img src="' . SITE_URL . '/assets/images/ott/bua.png" max-width="100%"/></label></td><td width="33%" align="center"><label for="s_3"><img src="' . SITE_URL . '/assets/images/ott/bao.png" max-width="100%"/></label></td></tr><tr valign="middle"><td width="33%" align="center"><input type="radio" name="select" value="1" id="s_1"/></td><td width="34%" align="center"><input type="radio" name="select" value="2" id="s_2"/></td><td width="33%" align="center"><input type="radio" name="select" value="3" id="s_3"/></td></tr></table>'.
	'<div class="menu"><input type="submit" name="submit" value="Chơi" class="btn btn-primary btn-block" /></div>' .
	'<input type="hidden" name="csrf_token" value="' . CSRF_TOKEN . '" />' .
	'</form>';

$tpl_file = 'game::rock-paper-scissors';
