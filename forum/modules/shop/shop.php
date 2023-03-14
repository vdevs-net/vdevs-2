<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Cửa hàng';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('Shop');
$_breadcrumb = $breadcrumb->out();

$tpl_data['menu'] = [
	[
		'name'  => 'Mua chức năng',
		'items' => [
			[
				'name' => 'Tạo logo wap</a> (200 xu)',
				'url'  => 'logo'
			],
		]
	],
	[
		'name' => 'Dịch vụ',
		'items' => [
			[
				'name' => 'Nạp thẻ',
				'url'  => 'charging'
			],
			[
				'name' => 'Mua xu',
				'url'  => 'buy-coin'
			],
			[
				'name' => 'Tặng xu',
				'url'  => 'send-coin'
			],
			[
				'name' => 'Lịch sử giao dịch',
				'url'  => 'history'
			]
		]
	]
];
$tpl_file = 'page.menu';
