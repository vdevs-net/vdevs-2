<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
// Check rights
if ($rights < 1) {
    $module_error = $lng['error_rights'];
} else {
    set_time_limit(60);
    define('_IS_MRKEN', 1);

    $lng = array_merge($lng, core::load_lng('admin'));
    $headmod = 'admin';
    $page_title = $lng['admin_panel'];
}