<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$lng = array_merge($lng, core::load_lng('faq'));
$page_title = 'FAQ';
$headmod = 'faq';
require(ROOTPATH . 'system/header.php');

// Back link
if (empty($_SESSION['ref'])) {
    $_SESSION['ref'] = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : SITE_URL;
}

$breadcrumb = new breadcrumb();

switch ($act) {
    case 'tags':
        $breadcrumb->add('/misc/help', 'FAQ');
        $breadcrumb->add($lng['tags']);
        $tpl_file = 'misc::bbcodes';
        break;

    case 'smileys':
        // The main menu catalog smileys
        $breadcrumb->add('/misc/help', 'FAQ');
        $emoticons = bbcode::get_emoticons();
        if (isset($_GET['cat'])) {
            $cat_list = array_keys($emoticons);
            $cat = in_array($_GET['cat'], $cat_list) ? $_GET['cat'] : $cat_list[0];
            $breadcrumb->add('/misc/help?act=smileys', $lng['smileys']);
            $breadcrumb->add(ucfirst(functions::checkout($emoticons[$cat]['name'])));
            $tpl_file = 'misc::smileys';

            $smileys = $emoticons[$cat]['items'];
            $total = count($smileys);
            $start = functions::fixStart($start, $total, $kmess);

            $tpl_data['total'] = $total;
            $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('help?act=smileys&cat=' . urlencode($cat) . '&page=', $start, $total, $kmess) : '');
            if ($total) {
                foreach (new LimitIterator(new ArrayIterator($smileys), $start, $kmess) as $key => $value) {
                    $tpl_data['items'][] = [
                        'src' => SITE_URL . '/assets/emoticons/' . $cat . '/' . $value['url'],
                        'symbol' => functions::checkout($key)
                    ];
                }
            }
        } else {
            $breadcrumb->add($lng['smileys']);
            $tpl_file = 'page.list';

            $tpl_data['items'] = [];
            foreach ($emoticons as $key => $val) {
                $tpl_data['items'][] = [
                    'url'  => 'help?act=smileys&cat=' . urlencode($key),
                    'name' => functions::checkout($val['name'])
                ];
            }
        }
        break;

    default:
        $breadcrumb->add('FAQ');
        $tpl_file = 'page.menu';
        $tpl_data['menu'] = [
            [
                'name' => 'Menu',
                'items' => [
                    [
                        'url' => 'help?act=tags',
                        'name' => $lng['tags']
                    ],
                    [
                        'url' => 'help?act=smileys',
                        'name' => $lng['smileys']
                    ]
                ]
            ]
        ];
}
$_breadcrumb = $breadcrumb->out();
