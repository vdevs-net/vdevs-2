<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (!$user_id) {
    $module_error = $lng['access_guest_forbidden'];
}

if (isset($_SESSION['ref'])) {
    unset($_SESSION['ref']);
}

$lng = array_merge($lng, core::load_lng('mail'));

$headmod = 'mail';
$page_title = $lng['mail'];


function formatsize($size)
{
    // Formatting file size
    if ($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 . ' Gb';
    } elseif ($size >= 1048576) {
        $size = round($size / 1048576 * 100) / 100 . ' Mb';
    } elseif ($size >= 1024) {
        $size = round($size / 1024 * 100) / 100 . ' Kb';
    } else {
        $size = $size . ' b';
    }

    return $size;
}

function parseFileName($var = '')
{
    if (empty($var)) {
        return FALSE;
    }
    $file_ext = pathinfo($var, PATHINFO_EXTENSION);
    $file_body = mb_substr($var, 0, mb_strripos($var, '.'));
    $info['filename'] = mb_strtolower(mb_substr(str_replace('.', '_', $file_body), 0, 32));
    $info['fileext'] = mb_strtolower($file_ext);

    return $info;
}