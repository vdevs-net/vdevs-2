<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/farm/', 'Nông trại');
$breadcrumb->add('Mở rộng');
$_breadcrumb = $breadcrumb->out();

switch ($id) {
	case 3:
		$name = 'đơn vị hồ cá';
		break;
	case 2:
		$name = 'đơn vị vật nuôi';
		break;
	default:
		$name = 'ô đất';
		$id = 1;
}

echo '<div class="farm_wrapper">';
if ($count[$id] < $max_space[$id]) {
	$pay = $space_cost[$id][$count[$id] - $init_space[$id]];
	if ($datauser['coin'] >= $pay) {
		if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			mysql_query('INSERT INTO `' . FARM_AREA_TABLE . '` SET
				`user_id` = "'. $user_id .'",
				`type` = "' . $id . '"
			');
			mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = "'. ($datauser['coin'] - $pay) .'" WHERE `id` = "'. $user_id .'"');
			header('Location: ' . SITE_URL . '/farm/'); exit;
		} else {
			$token = mt_rand(10000, 99999);
			$_SESSION['token'] = $token;
			echo '<div class="menu"><p>Bạn có chắc chắn muốn mua thêm một ' . $name . '? Giá của ' . $name . ' này là: '. $pay .' xu</p>' .
			'<form action="buy-plot?id=' . $id . '" method="post"><input type="hidden" name="token" value="'. $token .'" /><input type="submit" name="submit" value="Đồng ý" class="btn btn-primary" /><a href="' . SITE_URL . '/farm/" class="btn btn-warning">' . $lng['cancel'] . '</a></form></div>';
		}
	} else {
		echo '<div class="alert alert-warning">Bạn cần '. $pay .' xu mới có thể mua ' . $name . ' này!</div>';
	}
} else {
	echo '<div class="alert alert-warning">Số ' . $name . ' của bạn đã đạt tối đa!</div>';
}
echo '</div>';

$tpl_data['rendered_content'] = ob_get_clean();
