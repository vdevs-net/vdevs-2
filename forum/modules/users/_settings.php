<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (!$user_id && !$set['active']) {
    $module_error = $lng['access_guest_forbidden'];
}

$headmod = 'users';
