<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require(ROOTPATH . 'system/header.php');

$sft_product_per_level = 10;
$sft_product_count = 40 + $datauser['sft_level'] * $sft_product_per_level;
$sft_price = array(
	500, 2000, 4500, 8000, 12500, 18000, // 6
	24500, 32000, 40500, 55000, 66550, 79950 // 12
);
$sft_max_level = count($sft_price);

$breadcrumb = new breadcrumb();
$breadcrumb->add('/farm/', 'Nông trại');
if ($mod) {
	$breadcrumb->add('/farm/star-fruit-tree', 'Cây khế');
} else {
	$breadcrumb->add('Cây khế');
}
$_breadcrumb = $breadcrumb->out();

echo '<div class="farm">';
echo '<div class="center star_fruit_tree_2"><div><img src="' . SITE_URL . '/assets/farm/star_fruit_tree'. ( $sft_timer ? '' : '_1') .'.png" /></div><span class="textbox ib bold">Cây khế ' . ($datauser['sft_level'] ? 'Lv.' . $datauser['sft_level'] : '') . ($datauser['sft_level'] < $sft_max_level && $sft_timer && $mod != 'upgrade' ? ' - <a href="?mod=upgrade">Nâng cấp</a>':'') . '</strong></span></div><div class="controls">';
if ($sft_timer) {
	if ($mod == 'upgrade') {
		if ($datauser['sft_level'] < $sft_max_level) {
			if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && strlen($_POST['token']) > 3 && $_POST['token'] == $_SESSION['token'] && core::$user_agent !== core::DEFAULT_UA) {
				if ($datauser['coin'] >= $sft_price[$datauser['sft_level']]) {
					$datauser['coin'] -= $sft_price[$datauser['sft_level']];
					$datauser['sft_level']++;
					mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = "'. $datauser['coin'] .'", `sft_level` = "'. $datauser['sft_level'] .'" WHERE `id` = "' . $user_id . '" LIMIT 1');
					$_SESSION['update_success'] = true;
					header('Location: ' . SITE_URL . '/farm/star-fruit-tree'); exit;
				} else {
					echo '<div class="textbox bg-notif">Bạn cần '. $sft_price[$datauser['sft_level']] .' xu mới có thể nâng cấp cây khế!</div>';
				}
			} else {
				$token = mt_rand(10000, 99999);
				$_SESSION['token'] = $token;
				echo '<div class="textbox"><div>Cấp tiếp theo: '. ($datauser['sft_level'] + 1) .'<br/>Thời gian sinh trưởng: '. timer($sft_time - $sft_time_per_level, 1) .'<br/>Sản lượng: '. ($sft_product_count + $sft_product_per_level) .' quả (10 xu/quả).<br/>Phí nâng cấp: '. ($sft_price[$datauser['sft_level']]) .' xu</div><form action="?mod=upgrade" class="mt-5" method="post"><input type="submit" name="submit" value="Nâng cấp" /><input type="hidden" name="token" value = "'. $token .'" /></form></div>';
			}
		} else {
			echo '<div class="textbox">Cây khế đã đạt cấp tối đa!</div>';
		}
	} else {
		echo '<div class="textbox">';

		if (isset($_SESSION['update_success'])) {
			echo '<span style="color:#009900;font-weight:bold">Nâng cấp thành công!</span><br />';
			unset($_SESSION['update_success']);
		}

		echo 'Sản lượng: '. $sft_product_count .'<br/>Còn ' . timer($sft_timer, 2) . ' mới có thể thu hoạch</div>';
	}
} else {
	if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		mysql_query('UPDATE `' . FARM_WAREHOUSE_TABLE . '` SET `count` = (`count` + ' . $sft_product_count . ') WHERE `user_id` = "'. $user_id .'" AND `product_id` = "1" LIMIT 1');
		mysql_query('UPDATE `' . USERS_TABLE . '` SET `sft_time` = "'. TIME .'" WHERE `id` = "'. $user_id .'" LIMIT 1');
		echo '<div class="textbox">Thu hoạch thành công! Bạn nhận '. $sft_product_count .' quả khế vào kho!<br/><a href="?mod=upgrade">Nâng cấp</a> cây khế để tăng sản lượng và giảm thời gian sinh trưởng!</div>';
	} else {
		$token = mt_rand(10000, 99999);
		$_SESSION['token'] = $token;
		echo '<form action="star-fruit-tree" method="post"><input type="hidden" name="token" value = "'. $token .'" /><input type="submit" name="submit" value="Thu hoạch" /></form>';
	}
}
echo '</div></div>';

$tpl_data['rendered_content'] = ob_get_clean();
