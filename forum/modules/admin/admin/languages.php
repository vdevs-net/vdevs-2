<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['language_default']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    $lng_list = array();
    $lng_desc = array();
    foreach (glob(ROOTPATH . 'system/languages/*/_core.ini') as $val) {
        $dir = explode('/', dirname($val));
        $iso = array_pop($dir);
        $desc = parse_ini_file($val);
        $lng_list[$iso] = isset($desc['name']) && !empty($desc['name']) ? $desc['name'] : $iso;
        $lng_desc[$iso] = $desc;
    }
    $tpl_data['refresh'] = false;
    if (isset($_GET['refresh'])) {
        core::$lng_list = array();
        $tpl_data['refresh'] = true;
    }
    $lng_add = array_diff(array_keys($lng_list), array_keys(core::$lng_list));
    $lng_del = array_diff(array_keys(core::$lng_list), array_keys($lng_list));
    if (!empty($lng_add) || !empty($lng_del)) {
        if (!empty($lng_del) && in_array($set['lng'], $lng_del)) {
            // If system language has been deleted, set to first available
            mysql_query('UPDATE `cms_settings` SET `val` = "' . key($lng_list) . '" WHERE `key` = "lng" LIMIT 1');
        }
        mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string(serialize($lng_list)) . '" WHERE `key` = "lng_list" LIMIT 1');
    }

    if (IS_POST) {
        $iso = isset($_POST['iso']) ? trim($_POST['iso']) : false;
        if ($iso && array_key_exists($iso, $lng_list)) {
            mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string($iso) . '" WHERE `key` = "lng"');
        }
        header('Location: languages'); exit;
    } else {
        $tpl_file = 'admin::languages';
        $tpl_data['languages'] = $lng_desc;
        $tpl_data['set_lng'] = $set['lng'];
    }
}
