<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    header('Location: ' . SITE_URL); exit;
}

$headmod = 'login';
$page_title = $lng['login'];

if (empty($_SESSION['ref'])) {
    $_SESSION['ref'] = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : SITE_URL;
}