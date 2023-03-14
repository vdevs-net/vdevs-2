<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Tạo logo';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/shop/', 'Shop');

$type = isset($_GET['type']) ? trim($_GET['type']) : '';
switch ($type) {
	case 'zencms':
		$breadcrumb->add('/shop/logo', 'Tạo logo');
		$breadcrumb->add('ZenCMS');

		$text1 = isset($_POST['text1']) ? functions::checkin($_POST['text1']) : 'vDevs';
		$text2 = isset($_POST['text2']) ? functions::checkin($_POST['text2']) : '.Net';
		$text3 = isset($_POST['text3']) ? functions::checkin($_POST['text3']) : 'MXH vDevs';
		$position = isset($_POST['position']) ? abs(intval($_POST['position'])) : 60;

		$tpl_data['description'] = 'CHÚ Ý: bạn sẽ không cần trả thêm phí khi chỉnh sửa vị trí văn bản';
		$tpl_data['logo_src'] = SITE_URL . '/assets/logo/zencms.php?text1=' . urlencode($text1) . '&text2=' . urlencode($text2) . '&text3=' . urlencode($text3) . '&position=' . $position;
		$tpl_data['form_action'] = 'logo?type=zencms';
		$tpl_data['input_text1'] = functions::checkout($text1);
		$tpl_data['input_text2'] = functions::checkout($text2);
		$tpl_data['input_text3'] = functions::checkout($text3);
		$tpl_data['input_position'] = $position;
		$tpl_file = 'shop::logo.zencms';
		break;

	case 'facebook':
		$breadcrumb->add('/shop/logo', 'Tạo logo');
		$breadcrumb->add('Facebook');

		$text = isset($_POST['text']) ? functions::checkin($_POST['text']) : 'vDevs.net';
		$style = isset($_POST['style']) ? abs(intval($_POST['style'])) : 2;

		$tpl_data['logo_src'] = SITE_URL . '/assets/logo/facebook.php?text=' . urlencode($text) . '&style=' . $style;
		$tpl_data['form_action'] = 'logo?type=facebook';
		$tpl_data['input_text'] = functions::checkout($text);
		$tpl_data['input_style'] = $style;
		$tpl_file = 'shop::logo.facebook';
		break;

	default:
		$breadcrumb->add('Tạo logo');
		$tpl_data['menu'] = [
			[
				'name'  => 'Chọn kiểu logo',
				'items' => [
					[
						'name' => 'Logo zencms',
						'url'  => 'logo?type=zencms'
					],
					[
						'name' => 'Logo facebook',
						'url'  => 'logo?type=facebook'
					]
				]
			]
		];
		$tpl_file = 'page.menu';
}

$_breadcrumb = $breadcrumb->out();
