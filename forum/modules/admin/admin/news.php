<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['news_on_frontpage']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    $tpl_file = 'admin::news';
    $tpl_data['settings_saved'] = $tpl_data['settings_default'] = false;
    // Settings News
    if (!isset($set['news']) || isset($_GET['reset'])) {
        // set the default settings
        $settings = array (
            'view' => '1',
            'size' => '200',
            'quantity' => '3',
            'days' => '7',
            'breaks' => '1',
            'smileys' => '0',
            'tags' => '1',
            'kom' => '1'
        );
        mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string(serialize($settings)) . '" WHERE `key` = "news"');
        $tpl_data['settings_default'] = true;
    } elseif (IS_POST) {
        // accept the settings from the form
        $settings['view'] = isset($_POST['view']) && $_POST['view'] >= 0 && $_POST['view'] < 4 ? intval($_POST['view']) : 1;
        $settings['size'] = isset($_POST['size']) && $_POST['size'] >= 50 && $_POST['size'] <= 500 ? intval($_POST['size']) : 200;
        $settings['quantity'] = isset($_POST['quantity']) && $_POST['quantity'] > 0 && $_POST['quantity'] < 16 ? intval($_POST['quantity']) : 3;
        $settings['days'] = isset($_POST['days']) && $_POST['days'] >= 0 && $_POST['days'] < 16 ? intval($_POST['days']) : 7;
        $settings['breaks'] = isset($_POST['breaks']);
        $settings['smileys'] = isset($_POST['smileys']);
        $settings['tags'] = isset($_POST['tags']);
        $settings['kom'] = isset($_POST['kom']);
        mysql_query('UPDATE `cms_settings` SET `val` = "' . mysql_real_escape_string(serialize($settings)) . '" WHERE `key` = "news"');
        $tpl_data['settings_saved'] = true;
    } else {
        // Get your saved settings
        $settings = unserialize($set['news']);
    }
    $tpl_data['settings'] = $settings;
}
