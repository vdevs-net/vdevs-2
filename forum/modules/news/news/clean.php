<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['news'];
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/news', $lng['news']);
$breadcrumb->add($lng['clear']);
$_breadcrumb = $breadcrumb->out();

// Cleaning news
if ($rights >= RIGHTS_ADMIN) {
    if (IS_POST && TOKEN_VALID) {
        $cl = isset($_POST['cl']) ? intval($_POST['cl']) : 0;
        switch ($cl) {
            case '1':
            // Clean the news older than 1 week
                mysql_query('DELETE FROM `news` WHERE `time` <= "' . (SYSTEM_TIME - 604800) . '"');
                mysql_query('OPTIMIZE TABLE `news`');
                $tpl_data['page_content'] = $lng['clear_week_confirmation'];
                break;

            case '2':
                // Clean all news
                mysql_query('TRUNCATE TABLE `news`');
                $tpl_data['page_content'] = $lng['clear_all_confirmation'];
                break;

            default :
                // Clean the news older than 1 month
                mysql_query('DELETE FROM `news` WHERE `time` <= "' . (SYSTEM_TIME - 2592000) . '"');
                mysql_query('OPTIMIZE TABLE `news`;');
                $tpl_data['page_content'] = $lng['clear_month_confirmation'];
        }
        $tpl_file = 'page.success';
    } else {
        $tpl_file = 'page.confirm';
        $tpl_data['form_action'] = SITE_URL . '/news/clean';
        $tpl_data['confirm_text'] = 'Are you sure?';
        $tpl_data['cancel_url'] = SITE_URL . '/news/';
        $tpl_data['confirm_options'] = [
            [
                'title' => $lng['clear_param'],
                'items' => [
                    [
                        'type' => 'radio',
                        'name' => 'cl',
                        'value' => 0,
                        'explain' => $lng['clear_month']
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'cl',
                        'value' => 1,
                        'explain' => $lng['clear_week']
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'cl',
                        'value' => 2,
                        'explain' => $lng['clear_all']
                    ]
                ]
            ]
        ];
    }
} else {
    $error_rights = true;
}