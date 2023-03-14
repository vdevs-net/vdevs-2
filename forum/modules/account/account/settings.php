<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $page_title = $lng['settings'];
    require(ROOTPATH . 'system/header.php');
    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/account/', 'Tài khoản');
    $breadcrumb->add($lng['settings']);
    $_breadcrumb = $breadcrumb->out();

    $tpl_data['set_ok'] = false;

    if (IS_POST) {
        $set_user['smileys'] = isset($_POST['smileys']) ? 1 : 0;
        $set_user['direct_url'] = isset($_POST['direct_url']) ? 1 : 0;
        $set_user['field_h'] = isset($_POST['field_h']) ? abs(intval($_POST['field_h'])) : 3;
        $set_user['kmess'] = isset($_POST['kmess']) ? abs(intval($_POST['kmess'])) : 10;
        if ($set_user['kmess'] < 5) {
            $set_user['kmess'] = 5;
        } elseif ($set_user['kmess'] > 99) {
            $set_user['kmess'] = 99;
        }
        if ($set_user['field_h'] < 1) {
            $set_user['field_h'] = 1;
        } elseif ($set_user['field_h'] > 9) {
            $set_user['field_h'] = 9;
        }
        $set_user['theme'] = isset($_POST['theme']) && in_array($_POST['theme'], $theme_list) ? $_POST['theme'] : $set['theme_' . $device];

        // Set the language
        $lng_select = isset($_POST['iso']) ? trim($_POST['iso']) : false;
        if ($lng_select && array_key_exists($lng_select, core::$lng_list)) {
            $set_user['lng'] = $lng_select;
            unset($_SESSION['lng']);
        }

        // Save settings
        mysql_query('UPDATE `users` SET `set_user` = "' . mysql_real_escape_string(serialize($set_user)) . '" WHERE `id` = "' . $user_id . '"');
        $_SESSION['set_ok'] = 1;
        header('Location: settings'); exit;
    }
    if (isset($_SESSION['set_ok'])) {
        $tpl_data['set_ok'] = true;
        unset($_SESSION['set_ok']);
    }
    $tpl_file = 'account::settings';
    $tpl_data['form_action'] = 'settings';
    $tpl_data['lang_list'] = core::$lng_list;
    $set_user['lng'] = isset($set_user['lng']) ? $set_user['lng'] : core::$lng_iso;
    $tpl_data['set_user'] = $set_user;
    $tpl_data['theme_options'] = '';
    foreach ($theme_list as $theme) {
        $tpl_data['theme_options'] .= '<option value="' . $theme . '"' . ($set_user['theme'] == $theme ? ' selected="selected">' : '>') . $theme . '</option>';
    }
}