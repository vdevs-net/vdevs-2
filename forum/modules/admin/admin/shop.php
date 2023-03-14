<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add('Cài đặt cửa hàng');
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    $tpl_file = 'admin::shop';
    $tpl_data['settings_saved'] = false;
    if (IS_POST) {
        // Save the system settings
        $set['offer'] = abs(intval($_POST['offer']));
        mysql_query('UPDATE `cms_settings` SET `val`="' . $set['offer'] . '" WHERE `key` = "offer"');
        $tpl_data['settings_saved'] = true;
    }
    $tpl_data['set_offer'] = $set['offer'];
}