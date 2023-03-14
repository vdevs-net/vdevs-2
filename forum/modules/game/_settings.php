<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (!$user_id) {
    $module_error = $lng['access_guest_forbidden'];
} else {
    $headmod = 'game';
    $page_title = 'Game';
}
