<?php
defined('_IS_MRKEN') or die('Error: restricted access');


$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['antiflood_settings']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    $tpl_file = 'admin::antiflood';
    $set_af = isset($set['antiflood']) ? unserialize($set['antiflood']) : array ();
    $tpl_data['settings_saved'] = $tpl_data['settings_default'] = false;
    if (IS_POST) {
        // receive data from the form
        $set_af['mode'] = isset($_POST['mode']) && $_POST['mode'] > 0 && $_POST['mode'] < 5 ? intval($_POST['mode']) : 2;
        $set_af['day'] = isset($_POST['day']) ? intval($_POST['day']) : 5;
        $set_af['night'] = isset($_POST['night']) ? intval($_POST['night']) : 15;
        $set_af['dayfrom'] = isset($_POST['dayfrom']) ? intval($_POST['dayfrom']) : 10;
        $set_af['dayto'] = isset($_POST['dayto']) ? intval($_POST['dayto']) : 22;
        // Check the correctness of the data entry
        if ($set_af['day'] < 4) {
            $set_af['day'] = 4;
        }
        if ($set_af['day'] > 300) {
            $set_af['day'] = 300;
        }
        if ($set_af['night'] < 4) {
            $set_af['night'] = 4;
        }
        if ($set_af['night'] > 300) {
            $set_af['night'] = 300;
        }
        if ($set_af['dayfrom'] < 6) {
            $set_af['dayfrom'] = 6;
        }
        if ($set_af['dayfrom'] > 12) {
            $set_af['dayfrom'] = 12;
        }
        if ($set_af['dayto'] < 17) {
            $set_af['dayto'] = 17;
        }
        if ($set_af['dayto'] > 23) {
            $set_af['dayto'] = 23;
        }
        mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string(serialize($set_af)) . '" WHERE `key` = "antiflood" LIMIT 1');
        $tpl_data['settings_saved'] = true;
    } elseif (empty($set_af) || isset($_GET['reset'])) {
        // Set the default settings (if not specified in the system)
        $set_af['mode'] = 2;
        $set_af['day'] = 5;
        $set_af['night'] = 15;
        $set_af['dayfrom'] = 10;
        $set_af['dayto'] = 22;
        mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string(serialize($set_af)) . '" WHERE `key` = "antiflood" LIMIT 1');
        $tpl_data['settings_default'] = true;
    }
    $tpl_data['set_af'] = $set_af;
}
