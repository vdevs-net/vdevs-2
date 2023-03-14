<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Game';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('Game');
$_breadcrumb = $breadcrumb->out();

$tpl_data['menu'] = [
    [
        'name'  => 'Danh sách game',
        'items' => [
            [
                'name' => 'Oẳn tù tì',
                'url'  => 'rock-paper-scissors'
            ],
            [
                'name' => 'Oẳn tù tì Online',
                'url'  => 'rock-paper-scissors-online'
            ]
        ]
    ]
];
$tpl_file = 'page.menu';
