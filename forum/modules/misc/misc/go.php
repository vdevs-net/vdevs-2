<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$referer = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : SITE_URL;
$url = isset($_REQUEST['url']) ? strip_tags(rawurldecode(trim($_REQUEST['url']))) : false;

if (isset($_GET['lng'])) {
	// SELECT LANGUAGE
    $tpl_data['page_title'] = $lng['language_select'];
	$breadcrumb = new breadcrumb();
	$breadcrumb->add($lng['language_select']);
	$_breadcrumb = $breadcrumb->out();
    $tpl_file = 'misc::language_select';
    $tpl_data['form_action'] = $referer;
} elseif ($url) {
    // Redirect the links in the text, processed function tags ()
	if (IS_POST) {
		header('Location: ' . $url); exit;
	} else {
		$tpl_data['page_title'] = $lng['external_link'];
		$breadcrumb = new breadcrumb();
		$breadcrumb->add($lng['external_link']);
		$_breadcrumb = $breadcrumb->out();

		$tpl_file = 'misc::go';
		$tpl_data['form_action'] = SITE_PATH . '/misc/go?url=' . rawurlencode($url);
		$tpl_data['confirm_text'] = sprintf($lng['redirect'], functions::checkout($url));
		$tpl_data['cancel_url'] = $referer;
	}
} elseif ($id) {
	// Redirect for advertising link
	$req = mysql_query('SELECT `link`, `count` FROM `cms_ads` WHERE `id` = "' . $id . '" LIMIT 1');
	if (mysql_num_rows($req)) {
		$res = mysql_fetch_assoc($req);
		$count_link = $res['count'] + 1;
		mysql_query('UPDATE `cms_ads` SET `count` = "' . $count_link . '"  WHERE `id` = "' . $id . '"');
		header('Location: ' . $res['link']); exit;
	}
}