<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$lng = array_merge($lng, core::load_lng('faq'));
$page_title = $lng['terms'];
$headmod = 'terms';
require(ROOTPATH . 'system/header.php');

// Back link
if (empty($_SESSION['ref'])) {
    $_SESSION['ref'] = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : SITE_URL;
}

$breadcrumb = new breadcrumb();
$breadcrumb->add($lng['terms']);
$_breadcrumb = $breadcrumb->out();
$tpl_file = 'misc::terms';
