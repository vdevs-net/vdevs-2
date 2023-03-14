<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$allow_chat_pagination = false;
$allow_js_scroll = true;

$meta_tags[] = ['name' => 'name', 'value' => 'mobile-web-app-capable', 'content' => 'yes'];
$meta_tags[] = ['name' => 'name', 'value' => 'apple-mobile-web-app-capable', 'content' => 'yes'];

$html_links[] = ['rel' => 'stylesheet', 'href' => SITE_PATH . '/assets/css/font-awesome.min.css?t=' . VERSION];
$html_links[] = ['rel' => 'stylesheet', 'href' => THEME_PATH . '/css/bootstrap.min.css?t=' . VERSION];
$html_links[] = ['rel' => 'stylesheet', 'href' => THEME_PATH . '/css/style.css?t=' . VERSION];

$html_js[] = ['ext' => 1, 'content' => SITE_PATH . '/assets/javascript/jquery-2.2.4.min.js?t=' . VERSION];
$get = $_GET;
unset($get['module'], $get['module_file'], $get['module_action']);
$html_js[] = ['ext' => 0, 'content' => 'var vDevs = {}, queryString = {};
    window.paceOptions = {restartOnReplaceState: false};
    $.extend(vDevs, {BASE_URL: "' . SITE_PATH . '", API_URL: "' . API_URL . '", user_id: ' . $user_id . ', browser: "' . $device . '"})
'];
$html_js[] = ['ext' => 1, 'content' => THEME_PATH . '/javascript/plugins.js?t=' . VERSION];
$html_js[] = ['ext' => 1, 'content' => THEME_PATH . '/javascript/apps.js?t=' . VERSION];
