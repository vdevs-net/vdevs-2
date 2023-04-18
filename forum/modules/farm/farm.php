<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$stmt = mysql_query('SELECT * FROM `' . FARM_AREA_TABLE . '` WHERE `user_id` = "'. $user_id .'"');
$farm_data = [
	1 => [],
	2 => [],
	3 => []
];
while ($res = mysql_fetch_assoc($stmt)) {
	$farm_data[$res['type']][$res['id']] = $res;
}
if (isset($_POST['submit']) && isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token'] && core::$user_agent !== core::DEFAULT_UA) {
	// Check selected plots
	$area = [];
	$select = isset($_POST['select']) ? intval($_POST['select']) : 0;
	if (isset($farm_data[$select])) {
		$sel_type = $select;
	} else {
		$select = 0;
		$_area = isset($_POST['area']) && is_array($_POST['area']) ? $_POST['area'] : [];
		foreach ($farm_data as $k => $_data) {
			$sel_type = $k;
			foreach ($_area as $__area) {
				if (array_key_exists($__area, $_data)) {
					$area[] = $__area;
				}
			}
			if ($area) {
				break;
			}
		}
	}
	$error = false;
	// Check action
	$action = isset($_POST['action']) ? trim($_POST['action']) : '';
	if (!$area && !isset($farm_data[$select])) {
		$error = 'Chưa có đơn vị nào được chọn!';
	} else {
		// Check user plots
		$planted = false; // Check if at least one area planted
		$sel_id = array(); // Check select plots
		$can_action_0 = false; // can watering / feed
		$can_action_3 = false; // can harvest / sell
		$ns = array();
		foreach ($farm_data[$sel_type] as $res) {
			if ($select || in_array($res['id'], $area)) {
				$sel_id[] = $res['id']; // for plant
				if ($res['item_id'] && $res['dead_time'] > TIME) {
					if ($res['type'] == 1 && $res['grow_time'] >= TIME && $res['collect_time'] == 0) {
						$planted = true;
					}
					if ((TIME < $res['grow_time'] || $res['type'] != 1) && $action == 'action_0') {
						// id for watering / feed
						$can_action_0 = true;
						$ns[$res['id']] = $res;
					} elseif ($action == 'action_3' && (TIME >= $res['grow_time'] || $res['type'] != 1)) {
						//  for harvest
						$can_action_3 = true;
						$ns[$res['id']] = $res;
					}
				}
			}
		}
	}
	if (empty($sel_id)) {
		$error = $lng['error_wrong_data'];
	}
	if (!$error) {
		switch ($action) {
			case 'action_0':
				// watering / feed
				if ($can_action_0) {
					$sql = 'UPDATE `' . FARM_AREA_TABLE . '` SET `ns` = CASE ';
					$action_0_ids = [];
					foreach ($ns as $key => $data) {
						if ($sel_type != 1) {
							if ($datauser['coin'] > 0) {
								$sql .= 'WHEN `id` = "'. $key .'" THEN "' . ns($data) . '" ';
								$action_0_ids[] = $key;
								--$datauser['coin'];
								continue;
							}
							break;
						} else {
							$sql .= 'WHEN `id` = "'. $key .'" THEN "'. ns($data) .'" ';
							$action_0_ids[] = $key;
						}
					}
					$sql .= 'END, `effect_0_time` = "' . TIME . '" WHERE `id` IN (' . implode(', ', $action_0_ids) . ')';
					if ($action_0_ids) {
						mysql_query($sql);
						if ($sel_type != 1) {
							mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = "' . $datauser['coin'] . '" WHERE `id` = "' . $user_id . '" LIMIT 1');
						}
					}
				}
				header('Location: ' . SITE_URL . '/farm/'); exit;
				break;

			case 'action_3':
				// harvest / sell
				if ($can_action_3) {
					if ($sel_type == 1) {
						$cth = array();
						$sql = 'UPDATE `' . FARM_WAREHOUSE_TABLE . '` SET `count` = CASE ';
						foreach ($ns as $key => $data) {
							if (isset($cth[$farm_items[$data['item_id']]['product']])) {
								$cth[$farm_items[$data['item_id']]['product']] += ceil($farm_items[$data['item_id']]['product_count'] * ns($data) / 100);
							} else {
								$cth[$farm_items[$data['item_id']]['product']] = ceil($farm_items[$data['item_id']]['product_count'] * ns($data) / 100);
							}
						}
						$update = array_keys($cth);
						foreach ($cth as $upd => $val) {
							$sql .= 'WHEN `product_id` = "'. $upd .'" THEN (`count` + ' . $cth[$upd] . ') ';
						}
						$sql .= 'END WHERE `product_id` IN (' . implode(', ', $update) . ') AND `user_id` = "' . $user_id . '"';
						mysql_query($sql);
						mysql_query('UPDATE `' . FARM_AREA_TABLE . '` SET `collect_time` = "' . TIME . '" WHERE `id` IN (' . implode(', ', array_keys($ns)) . ')');
					} else {
						$price = 0;
						foreach ($ns as $key => $data) {
							$price += cacl_price($data);
						}
						mysql_query('UPDATE `' . FARM_AREA_TABLE . '` SET `item_id` = "0", `time` = "0", `grow_time` = "0", `effect_0_time` = "0", `collect_time` = "0", `dead_time` = "0", `ns` = "100" WHERE `id` IN (' . implode(', ', array_keys($ns)) . ')');
						mysql_query('UPDATE `' . USERS_TABLE . '` SET `coin` = (`coin` + ' . $price . ') WHERE `id` = "' . $user_id . '" LIMIT 1');
					}
				}
				header('Location: ' . SITE_URL . '/farm/'); exit;
				break;

			default:
				$error = false;
				if ($planted) {
					$error = 'Ô đất bạn chọn đang có cây trồng!';
				}
				if (!$error) {
					$tree = abs(intval($action));
					if ($sel_type != 1 || !$tree) {
						$error = $lng['error_wrong_data'];
					}
				}
				if (!$error) {
					$stmt = mysql_query('SELECT `id`, `count` FROM `' . FARM_ITEMS_TABLE . '` WHERE `item_id` = "' . $tree . '" AND `user_id` = "'. $user_id .'" AND `type` = "1" AND `count` > 0 LIMIT 1');
					if (!mysql_num_rows($stmt)) {
						$error = 'Hạt giống bạn chọn chưa có!';
					}
				}
				if (!$error) {
					$res2 = mysql_fetch_assoc($stmt);
					$plant = min($res2['count'], count($sel_id));
					mysql_query('UPDATE `' . FARM_ITEMS_TABLE . '` SET `count` = "'. ($res2['count'] - $plant)  .'" WHERE `id` = "'. $res2['id'] .'" LIMIT 1');
					$plant_id = array();
					foreach ($sel_id as $sel) {
						if ($plant > 0) {
							$plant_id[] = $sel;
							$plant--;
							continue;
						}
						break;
					}
					mysql_query('UPDATE `' . FARM_AREA_TABLE . '` SET `item_id` = "'. $tree .'", `time` = "' . TIME . '", `grow_time` = "' . (TIME + $farm_items[$tree]['grow_time']) . '", `effect_0_time` = "'. TIME .'", `dead_time` = "' . (TIME + $farm_items[$tree]['dead_time']) . '", `collect_time` = "0", `ns` = "100"  WHERE `id` IN ('. implode(', ', $plant_id) .')');
					header('Location: ' . SITE_URL . '/farm/'); exit;
				} else {
					require(ROOTPATH . 'system/header.php');
					$breadcrumb = new breadcrumb();
					$breadcrumb->add('/farm/', 'Nông trại');
					$breadcrumb->add($lng['error']);
					$_breadcrumb = $breadcrumb->out();
					echo '<div class="farm_wrapper"><div class="alert alert-warning">' .
						functions::display_error($error, '<a href="' . SITE_URL . '/farm/">' . $lng['back'] . '</a>') .
						'</div></div>';
				}
		}
	} else {
		require(ROOTPATH . 'system/header.php');
		$breadcrumb = new breadcrumb();
		$breadcrumb->add('/farm/', 'Nông trại');
		$breadcrumb->add($lng['error']);
		$_breadcrumb = $breadcrumb->out();
		echo '<div class="farm_wrapper"><div class="alert alert-warning">' .
			functions::display_error($lng['error_wrong_data'], '<a href="' . SITE_URL . '/farm/">' . $lng['back'] . '</a>') .
			'</div></div>';
	}
} elseif (isset($_GET['collect'])) {
	// collect products
	$collect = abs(intval($_GET['collect']));
	$product_count = 0;
	$collect_ids = [];
	if (isset($farm_items[$collect]) && $farm_items[$collect]['product']) {
		foreach ($farm_data[2] as $k => $v) {
			if ($v['item_id'] == $collect && $v['grow_time'] <= TIME && TIME - $v['collect_time'] >= $farm_items[$collect]['product_interval']) {
				$product_count += ceil($farm_items[$collect]['product_count'] * ns($v) / 100);
				$collect_ids[] = $k;
			}
		}
	}
	if ($product_count) {
		mysql_query('UPDATE `' . FARM_WAREHOUSE_TABLE . '` SET `count` = (`count` + ' . $product_count . ') WHERE `product_id` = "' . $farm_items[$collect]['product'] . '" AND `user_id` = "' . $user_id . '" LIMIT 1');
		mysql_query('UPDATE `' . FARM_AREA_TABLE . '` SET `collect_time` = "' . TIME . '" WHERE `id` IN (' . implode(', ', $collect_ids) . ')');
	}
	header('Location: ' . SITE_URL . '/farm/'); exit;
} else {
	require(ROOTPATH . 'system/header.php');
	$data = [];
	$breadcrumb = new breadcrumb();
	$breadcrumb->add('Nông trại');
	$_breadcrumb = $breadcrumb->out();

	echo '<div class="farm_wrapper"><form action="' . SITE_URL . '/farm/" method="post">';
	echo '<div class="farm">'.
		'<div class="farm_bg"><marquee behavior="scroll" direction="left" scrollamount="1" class="cloud_1"><img src="' . SITE_URL . '/assets/farm/cloud_1.png"></marquee><marquee behavior="scroll" direction="left" scrollamount="2" class="cloud_2"><img src="' . SITE_URL . '/assets/farm/cloud_2.png"></marquee></div>' .
		'<div class="farm_body"><div class="construction"><a href="' . SITE_URL . '/farm/store" class="to_store"></a><a href="' . SITE_URL . '/farm/warehouse" class="to_warehouse"></a><a href="' . SITE_URL . '/farm/star-fruit-tree" class="to_star_fruit_tree'. ($sft_timer ? '' : ' star_fruit_tree_1') .'"><span class="timer" id="timer" data-timer="'. $sft_timer .'">'. ($sft_timer ? timer($sft_timer) : 'Đã chín!') .'</span></a></div>' .
		'<div class="plant_area">';
	foreach ($farm_data[1] as $res) {
		if ($id && $res['id'] == $id) {
			$data = $res;
		}
		echo '<label class="plot"><a href="?id='. $res['id'] .'" class="item_'. $res['item_id'] .'" style="background-image:url(' . SITE_URL . '/assets/farm/item/' . $res['item_id'] . '_' . status($res) . '.png)"></a><input type="checkbox" name="area[]" value="'. $res['id'] .'"'. ($id == $res['id'] ? ' checked ':'') .'></label>';
	}
	echo '</div><!--/ plant area -->' .
		'<div class="farm_divide"></div>' .
		'<div class="farm_bottom"><div class="farm_cote">';
	$has_poultries = $can_collect_eggs = false;
	foreach ($farm_data[2] as $res) {
		if ($res['item_id'] && $res['dead_time'] > TIME) {
			if ($id && $res['id'] == $id) {
				$data = $res;
				echo '<input type="hidden" name="area[]" value="'. $res['id'] .'" />';
			}
			if ($res['item_id'] == 12) {
				$has_poultries = true;
				if ($res['grow_time'] <= TIME && $res['collect_time'] < TIME - $farm_items[$res['item_id']]['product_interval']) {
					$can_collect_eggs = $res['item_id'];
				}
			}
			$left = mt_rand(10, 131);
			$top = mt_rand(10, 74);
			echo '<a class="farm_pond_item" style="top: ' . $top . 'px; left: ' . $left . 'px" href="?id=' . $res['id'] . '"><img src="' . SITE_URL . '/assets/farm/item/' . $res['item_id'] . '_' . status($res) . '.gif" /></a>';
		}
	}
	if ($has_poultries) {
		echo '<a class="farm_pond_item" style="top:20px;left:12px;z-index:1" href="?collect=12"><img src="' . SITE_URL . '/assets/farm/nest_' . ($can_collect_eggs ? '1' : '0') . '.png" /></a>';
	}
	echo '</div><div class="farm_pond">';
	foreach ($farm_data[3] as $res) {
		if ($res['item_id'] && $res['dead_time'] > TIME) {
			if ($id && $res['id'] == $id) {
				$data = $res;
				echo '<input type="hidden" name="area[]" value="'. $res['id'] .'" />';
			}
			$left = mt_rand(10, 72);
			$top = mt_rand(10, 55);
			echo '<a class="farm_pond_item" style="top: ' . $top . 'px; left: ' . $left . 'px" href="?id=' . $res['id'] . '"><img src="' . SITE_URL . '/assets/farm/item/' . $res['item_id'] . '_' . status($res) . '.gif" /></a>';
		}
	}
	echo '</div></div>' .
		'</div></div><!-- end .farm -->';

	$ns = false;

	if ($data) {
		echo '<div class="phdr">Thông tin đơn vị số <b>' . $data['id'] . '</b></div>';
		$ns = ns($data);
		if (!$ns) {
			echo '<div class="menu">Chưa có</div>';
		} else {
			$effect_0_time = TIME - $data['effect_0_time'];
			$interval = ($data['grow_time'] - $data['time']) / 3;
			// min effect 0 time = 12 hours
			if ($interval > $min_effect_0_time) {
				$interval = $min_effect_0_time;
			}
			echo '<div class="menu">'. $farm_items[$data['item_id']]['name'] . ' (' . ($data['type'] != 1 ? 'sức khỏe' : 'năng suất') . ': '. $ns .'%)' . ($effect_0_time > $interval ? ($data['type'] != 1 ? ' - (Đang đói)' : ($data['grow_time'] > TIME ? ' - (Đang thiếu nước)' : '')) : '') . '</div>' .
				'<div class="menu">'. ($data['grow_time'] > TIME ? 'Thời gian lớn: '. timer($data['grow_time'] - TIME, 1) : ($data['type'] != 1 ? 'Đã trưởng thành' : 'Đã chín')) .'</div>' .
				'<div class="menu">Thời gian sống: ' . timer($data['dead_time'] - TIME, 1) . '</div>' .
				($data['type'] != 1 ? '<div class="menu">Giá bán hiện tại: ' . cacl_price($data) . ' xu</div>' : '') .
				'<!-- end item info -->';
		}
	}
	echo '<div class="phdr">Hành động</div>';
	echo '<div class="menu form-inline"><select name="select" class="custom-select form-control md-up-margin-right"><option value="0">' . ($data && $ns ? 'Đơn vị đang chọn' : 'Chọn mục tiêu') . '</option>' .
	    '<option value="1">Tất cả ô đất</option><option value="2">Tất cả vật nuôi</option><option value="3">Tất cả cá</option></select>' . 
	    '<select name="action" class="custom-select form-control xs-margin-top md-up-margin-right"><option value="0">Chọn hành động</option><option value="action_0">Tưới nước/Cho ăn</option>';
	$count2 = mysql_result(mysql_query('SELECT COUNT(*) FROM `' . FARM_ITEMS_TABLE . '` WHERE `user_id` = "'. $user_id .'" AND `type` = "1" AND `count` > 0'), 0);
	if ($count2) {
		$stmt = mysql_query('SELECT `item_id`, `count` FROM `' . FARM_ITEMS_TABLE . '` WHERE `user_id` = "'. $user_id .'" AND `type` = "1" AND `count` > "0"');
		while ($res2 = mysql_fetch_assoc($stmt)) {
			echo '<option value="' . $res2['item_id'] . '">Trồng '. $farm_items[$res2['item_id']]['name'] .' ('. $res2['count'] .')</option>';
		}
	}
	$token = mt_rand(10000, 99999);
	$_SESSION['token'] = $token;
	$options = [];
	if ($count[1] < $max_space[1]) {
		$options[] = '<option value="1">ô đất</option>';
	}
	if ($count[2] < $max_space[2]) {
		$options[] = '<option value="2">chuồng trại</option>';
	}
	if ($count[3] < $max_space[3]) {
		$options[] = '<option value="3">hồ cá</option>';
	}
	echo '<option value="action_3">Thu hoạch/Bán</option></select><input type="hidden" name="token" value="'. $token .'" /><input type="submit" name="submit" value="Thực hiện" class="btn btn-primary xs-margin-top" /></div></form>' .
		'<!-- end form -->' .
		($options ? '<div class="menu"><form action="' . SITE_URL . '/farm/buy-plot" method="get" class="form-inline"><label class="mr-2 md-up-margin-right">Mở rộng:</label><select name="id" class="custom-select form-control xs-margin-top mr-2 md-up-margin-right">' . implode('', $options) . '</select><input type="submit" value="Mua" class="btn btn-primary xs-margin-top" /></form></div>' : '') .
		'<div class="menu"><a href="' . SITE_URL . '/farm/guide">Hướng dẫn</a></div>' .
		'<!-- end options --></div><!-- end .farm_wrapper -->';
}

$tpl_data['rendered_content'] = ob_get_clean();
