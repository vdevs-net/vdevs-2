<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['access_rights']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    $tpl_file = 'admin::access';
    $tpl_data['settings_saved'] = false;
    if (IS_POST) {
        // Write in the database
        mysql_query('UPDATE `cms_settings` SET `val`="' . (isset($_POST['reg']) ? intval($_POST['reg']) : 0) . '" WHERE `key`="mod_reg"');
        mysql_query('UPDATE `cms_settings` SET `val`="' . (isset($_POST['forum']) ? intval($_POST['forum']) : 0) . '" WHERE `key`="mod_forum"');
        mysql_query('UPDATE `cms_settings` SET `val`="' . (isset($_POST['active']) ? intval($_POST['active']) : 0) . '" WHERE `key`="active"');
        mysql_query('UPDATE `cms_settings` SET `val`="' . (isset($_POST['access']) ? intval($_POST['access']) : 0) . '" WHERE `key`="site_access"');
        $req = mysql_query('SELECT * FROM `cms_settings`');
        $set = array();
        while ($res = mysql_fetch_assoc($req)) {
            $set[$res['key']] = $res['val'];
        }
        $tpl_data['settings_saved'] = true;
    }
    $tpl_data['set_mod_forum']    = $set['mod_forum'];
    $tpl_data['set_active']       = $set['active'];
    $tpl_data['set_mod_reg']      = $set['mod_reg'];
    $tpl_data['set_site_access']  = $set['site_access'];
}
