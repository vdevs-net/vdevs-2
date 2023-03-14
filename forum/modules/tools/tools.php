<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Công cụ';

$breadcrumb = new breadcrumb();
$breadcrumb->add('Công cụ');
$_breadcrumb = $breadcrumb->out();

$tpl_file = 'page.menu';
$tpl_data['menu'] = [
    [
        'name' => 'Image tools',
        'items' => [
            [
                'name' => 'Upload ảnh',
                'url' => SITE_URL . '/tools/image-upload/'
            ]
        ]
    ]
];
