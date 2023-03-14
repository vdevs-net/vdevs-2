<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['news'];
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/news', $lng['news']);
$breadcrumb->add($lng['delete']);
$_breadcrumb = $breadcrumb->out();

if ($rights >= 6) {
    if (IS_POST && TOKEN_VALID) {
        mysql_query('DELETE FROM `news` WHERE `id` = "' . $id . '"');
        $tpl_file = 'page.success';
        $tpl_data['page_content'] = $lng['article_deleted'];
    } else {
        $tpl_file = 'page.confirm';
        $tpl_data['form_action']     = SITE_URL . '/news/' . $id . '/delete';
        $tpl_data['confirm_text']    = $lng['delete_confirmation'];
        $tpl_data['cancel_url']      = SITE_URL . '/news/';
    }
} else {
    $error_rights = true;
}