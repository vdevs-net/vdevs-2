<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['news'];
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/news', $lng['news']);
$breadcrumb->add($lng['edit']);
$_breadcrumb = $breadcrumb->out();


if ($rights >= RIGHTS_SUPER_MODER) {
    if ($id) {
        $req = mysql_query('SELECT `name`, `text` FROM `news` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $error = array();
            $name = isset($_POST['name']) ? functions::checkin($_POST['name']) : '';
            $text = isset($_POST['text']) ? functions::checkin($_POST['text']) : '';
            if (IS_POST) {
                if (empty($name)) {
                    $error[] = $lng['error_title'];
                }
                if (empty($text)) {
                    $error[] = $lng['error_text'];
                }
                if (empty($error)) {
                    mysql_query('UPDATE `news` SET
                        `name` = "' . mysql_real_escape_string($name) . '",
                        `text` = "' . mysql_real_escape_string($text) . '"
                        WHERE `id` = "' . $id . '"
                    ');
                    $tpl_data['page_content'] = $lng['article_changed'];
                    $tpl_file = 'page.success';
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = functions::display_error($error);
                }
            } else {
                $res = mysql_fetch_assoc($req);
                $tpl_data['form_action']  = SITE_URL . '/news/' . $id . '/edit';
                $tpl_data['news_title']   = functions::checkout($res['name']);
                $tpl_data['news_content'] = functions::checkout($res['text']);
                $tpl_file = 'news::edit';
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($lng['error_wrong_data']);
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = functions::display_error($lng['error_wrong_data']);
    }
} else {
    $error_rights = true;
}