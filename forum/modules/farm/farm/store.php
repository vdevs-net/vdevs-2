<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Cửa hàng nông trại';
require(ROOTPATH . 'system/header.php');
$max_count = 99;
$lng['coin'] = 'xu';
$lng['gold'] = 'lượng';
$html = '<div class="farm_wrapper">';
if ($id) {
	if (isset($farm_items[$id])) {
		$res_i = $farm_items[$id];
		$curr = $res_i['currency'] ? 'gold' : 'coin';
		$is_animal = ($res_i['type'] == 2 || $res_i['type'] == 3);
		if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$error = false;
			$amount = isset($_POST['amount']) ? abs(intval($_POST['amount'])) : 1;
			if ($amount < 1 || $amount > $max_count) {
				$error = 'Số lượng mua phải từ 1 đến ' . $max_count . '!';
			} else {
				$pay = $amount * $res_i['cost'];
				if ($pay > $datauser[$curr]) {
					$error = 'Bạn không đủ '. $lng[$curr] .' để mua!';
				}
			}
			if (!$error) {
				// Update count
				if ($is_animal) {
					$stmt = mysql_query('SELECT `item_id` FROM `' . FARM_AREA_TABLE . '` WHERE (`item_id` = 0 OR (`item_id` != 0 AND `dead_time` > ' . TIME . ')) AND `type` = "' . $res_i['type'] . '" AND `user_id` = "' . $user_id . '"');
					$space = 0;
					while ($res = mysql_fetch_assoc($stmt)) {
						if ($res['item_id']) {
							$space += $farm_items[$res['item_id']]['size'];
						}
					}
					if ($space + $amount * $res_i['size'] > $count[$res_i['type']]) {
						$html .= '<div class="alert alert-warning">Số đơn vị nông trại không đủ. Bạn chỉ còn ' . ($count[$res_i['type']] - $space) . ' đơn vị. Vui lòng <a href="store?id=' . $id . '">thử lại</a>!</a></div>';
					} else {
						mysql_query('UPDATE `' . FARM_AREA_TABLE . '` SET
							`item_id` = "' . $id . '",
							`time`    = "' . TIME . '",
							`grow_time` = "' . (TIME + $res_i['grow_time']) . '",
							`effect_0_time` = "' . TIME . '",
							`dead_time` = "' . (TIME + $res_i['dead_time']) . '"
							WHERE (`item_id` = 0 OR `dead_time` <= ' . TIME . ') AND `type` = "' . $res_i['type'] . '" AND `user_id` = "' . $user_id . '"
							LIMIT ' . $amount . '
						');
						// Update money
						$datauser[$curr] -= $pay;
						mysql_query('UPDATE `' . USERS_TABLE . '` SET `'. $curr .'` = "'. $datauser[$curr] .'" WHERE `id` = "'. $user_id .'"');
						$html .= '<div class="alert alert-success">Mua thành công! Bạn bị trừ ' . $pay . ' ' . $lng[$curr] . '!<br /><a href="store">Mua tiếp</a></div>';
					}
				} else {
					$stmt = mysql_query('SELECT `id`, `count` FROM `' . FARM_ITEMS_TABLE . '` WHERE `user_id` = "'. $user_id .'" AND `item_id` = "'. $id .'" LIMIT 1');
					$res = mysql_fetch_assoc($stmt);
					if ($res['count'] + $amount > $max_count) {
						$html .= '<div class="alert alert-warning">Hiện bạn đang có '. $res['count'] .' vật phẩm này. Bạn chỉ có thể mua thêm tối đa '. ($max_count - $res['count']) .' vật phẩm!</div>';
					} else {
						mysql_query('UPDATE `' . FARM_ITEMS_TABLE . '` SET `count` = "'. ($res['count'] + $amount) .'" WHERE `id` = "'. $res['id'] .'"');
						// Update money
						$datauser[$curr] -= $pay;
						mysql_query('UPDATE `' . USERS_TABLE . '` SET `'. $curr .'` = "'. $datauser[$curr] .'" WHERE `id` = "'. $user_id .'"');
						$html .= '<div class="alert alert-success">Mua thành công! Bạn bị trừ ' . $pay . ' ' . $lng[$curr] . '!<br /><a href="store">Mua tiếp</a></div>';
					}
				}
			} else {
				$html .= '<div class="alert alert-warning">' . $error . '</div>';
			}
		} else {
			$token = mt_rand(10000, 99999);
			$_SESSION['token'] = $token;
			$html .= '<div class="menu"><img src="' . SITE_URL . '/assets/farm/item/i_'. $id .'.png" /> <b>'. $res_i['name'] .'</b> ('. ($res_i['grow_time'] / 3600) .' giờ)<br />Giá: '. $res_i['cost'] .' ' . $lng[$curr] . ($res_i['type'] == 1 ? ' - Sản lượng: '. $res_i['product_count'] : '<br/>Thời gian lớn: ' . ($res_i['grow_time'] / 86400) . ' ngày. Bán: ' . $res_i['price'] . ' xu. ' . $res_i['size'] . ' đơn vị ' . ($res_i['type'] == 2 ? 'nông trại' : 'hồ cá') . '.' . ($res_i['product'] ? ' Sản phẩm: ' . $res_i['product_count'] . ' ' . $farm_products[$res_i['product']]['name'] . ' sau mỗi ' . ($res_i['product_interval'] / 3600) . ' giờ. Giá sản phẩm: ' . $farm_products[$res_i['product']]['price'] . ' xu / ' . $farm_products[$res_i['product']]['name'] . '.' : '') . ' Thời gian sống: ' . ($res_i['dead_time'] / 86400) . ' ngày') . '<hr style="margin-top:4px;margin-bottom:4px" /><form action="store?id='. $id .'" method="post" class="form-inline"><input type="number" min="1" max="99" name="amount" required autocomplete="off" size="3" class="form-control mr-2" /><input type="submit" name="submit" value="Mua" class="btn btn-primary" /><input type="hidden" name="token" value="'. $token .'" /></form></div>';
		}
	} else {
		$html .= '<div class="alert alert-warning">Vật phẩm không tồn tại!</div>';
	}
} else {
	$total = count($farm_items);
	if ($total) {
        $start = functions::fixStart($start, $total, $kmess);
        $max_page = ceil($total / $kmess);
        if ($page > $max_page) {
            $page = $max_page;
        }

		$pagination = functions::display_pagination('store?page=', $start, $total, $kmess);
	    $items = new LimitIterator(new ArrayIterator($farm_items), ($page - 1) * $kmess, $kmess);
		foreach ($items as $key => $value) {
			$curr = $value['currency'] ? 'gold' : 'coin';
			$html .= '<div class="menu"><img src="' . SITE_URL . '/assets/farm/item/i_'. $key .'.png" /> <a href="store?id='. $key .'">'. $value['name'] .'</a> ('. ($value['grow_time'] / 3600) .' giờ)<br />Giá: '. $value['cost'] . ' ' . $lng[$curr] . ($value['type'] == 1 ? ' - Sản lượng: '. $value['product_count'] : '<br/>Thời gian lớn: ' . ($value['grow_time'] / 86400) . ' ngày. Bán: ' . $value['price'] . ' xu. ' . $value['size'] . ' đơn vị ' . ($value['type'] == 2 ? 'nông trại' : 'hồ cá') . '.' . ($value['product'] ? ' Sản phẩm: ' . $value['product_count'] . ' ' . $farm_products[$value['product']]['name'] . ' sau mỗi ' . ($value['product_interval'] / 3600) . ' giờ. Giá sản phẩm: ' . $farm_products[$value['product']]['price'] . ' xu / ' . $farm_products[$value['product']]['name'] . '.' : '') . ' Thời gian sống: ' . ($value['dead_time'] / 86400) . ' ngày') . '</div>';
		}
	} else {
		$html .= '<div class="alert alert-warning">Cửa hàng tạm thời đóng cửa!</div>';
	}
	if ($total > $kmess) {
		$html .= '<div class="action">'. $pagination . '</div>';
	}
}
$html .= '</div>';



$breadcrumb = new breadcrumb();
$breadcrumb->add('/farm/', 'Nông trại');
if ($id) {
	$breadcrumb->add('/farm/store', 'Cửa hàng');
} else {
	$breadcrumb->add('Cửa hàng');
}
$_breadcrumb = $breadcrumb->out();

echo $html;

$tpl_data['rendered_content'] = ob_get_clean();
