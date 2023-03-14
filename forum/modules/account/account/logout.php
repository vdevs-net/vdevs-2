<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $referer = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : SITE_URL;

    if (IS_POST && TOKEN_VALID) {
        setcookie('cuid', '', SYSTEM_TIME - 1, COOKIE_PATH);
        setcookie('cups', '', SYSTEM_TIME - 1, COOKIE_PATH);
        session_destroy();
        header('Location: ' . SITE_URL); exit;
    } else {
        $breadcrumb = new breadcrumb();
        $breadcrumb->add($lng['logout']);
        $_breadcrumb = $breadcrumb->out();

        $tpl_file = 'account::logout';
        $tpl_data['form_action'] = 'logout';
        $tpl_data['cancel_url'] = $referer;
    }
}