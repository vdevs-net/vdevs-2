<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['site_settings']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    $tpl_file = 'admin::settings';
    $tpl_data['settings_saved'] = false;
    if (IS_POST) {
        // Save the system settings
        $copyright = isset($_POST['copyright']) ? functions::checkin($_POST['copyright']) : '';
        $meta_key = isset($_POST['meta_key']) ? functions::checkin($_POST['meta_key']) : '';
        $meta_desc = isset($_POST['meta_desc']) ? functions::checkin($_POST['meta_desc']) : '';
        $madm = isset($_POST['madm']) ? functions::checkin($_POST['madm']) : '';
        $theme_wap = isset($_POST['theme_wap']) && in_array($_POST['theme_wap'], $theme_list) ? $_POST['theme_wap'] : '';
        $theme_touch = isset($_POST['theme_touch']) && in_array($_POST['theme_touch'], $theme_list) ? $_POST['theme_touch'] : '';
        $theme_web = isset($_POST['theme_web']) && in_array($_POST['theme_web'], $theme_list) ? $_POST['theme_web'] : '';
        if ($theme_wap) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . $theme_wap . '" WHERE `key` = "theme_wap"');
        }
        if ($theme_touch) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . $theme_touch . '" WHERE `key` = "theme_touch"');
        }
        if ($theme_web) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . $theme_web . '" WHERE `key` = "theme_web"');
        }
        if (filter_var($madm, FILTER_VALIDATE_EMAIL) === false) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string($madm) . '" WHERE `key` = "email"');
        }
        if (!empty($copyright)) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string($copyright) . '" WHERE `key` = "copyright"');
        }
        mysql_query('UPDATE `cms_settings` SET `val` = "' . abs(intval($_POST['flsz'])) . '" WHERE `key` = "flsz"');
        mysql_query('UPDATE `cms_settings` SET `val` = "' . (isset($_POST['gz']) ? 1 : 0) . '" WHERE `key` = "gzip"');
        if (!empty($meta_key)) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string($meta_key) . '" WHERE `key` = "meta_key"');
        }
        if (!empty($meta_desc)) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string($meta_desc) . '" WHERE `key` = "meta_desc"');
        }
        $req = mysql_query('SELECT * FROM `cms_settings`');
        $set = array();
        while ($res = mysql_fetch_assoc($req)) {
            $set[$res['key']] = $res['val'];
        }
        $tpl_data['settings_saved'] = true;
    }
    $tpl_data['set_copyright'] = htmlspecialchars($set['copyright']);
    $tpl_data['set_email'] = htmlspecialchars($set['email']);
    $tpl_data['set_flsz'] = intval($set['flsz']);
    $tpl_data['set_gzip'] = $set['gzip'];
    $tpl_data['set_meta_key'] = htmlspecialchars($set['meta_key']);
    $tpl_data['set_meta_desc'] = htmlspecialchars($set['meta_desc']);
    $tpl_data['set_theme_wap'] = $set['theme_wap'];
    $tpl_data['set_theme_touch'] = $set['theme_touch'];
    $tpl_data['set_theme_web'] = $set['theme_web'];
    $tpl_data['theme_list'] = $theme_list;
}
