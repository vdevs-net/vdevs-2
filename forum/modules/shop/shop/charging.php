<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Nạp thẻ';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/shop/', 'Shop');
$breadcrumb->add('Nạp thẻ');
$_breadcrumb = $breadcrumb->out();

$tpl_data['page_content'] = 'Đang bảo trì!';
$tpl_file = 'page.error';
