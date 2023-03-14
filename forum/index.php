<?php
define('_MRKEN_CMS', 1);
require('system/core.php');

$templates = new League\Plates\Engine(TEMPLATE_DIR);
$templates->addFolder('account', TEMPLATE_DIR . '/account');
$templates->addFolder('admin', TEMPLATE_DIR . '/admin');
$templates->addFolder('chat', TEMPLATE_DIR . '/chat');
$templates->addFolder('forum', TEMPLATE_DIR . '/forum');
$templates->addFolder('farm', TEMPLATE_DIR . '/farm');
$templates->addFolder('game',  TEMPLATE_DIR . '/game');
$templates->addFolder('home',  TEMPLATE_DIR . '/home');
$templates->addFolder('login',  TEMPLATE_DIR . '/login');
$templates->addFolder('messages',  TEMPLATE_DIR . '/messages');
$templates->addFolder('misc',  TEMPLATE_DIR . '/misc');
$templates->addFolder('news',  TEMPLATE_DIR . '/news');
$templates->addFolder('profile',  TEMPLATE_DIR . '/profile');
$templates->addFolder('shop',  TEMPLATE_DIR . '/shop');
$templates->addFolder('tools',  TEMPLATE_DIR . '/tools');
$templates->addFolder('users',  TEMPLATE_DIR . '/users');

$templates->registerFunction('display_tab', function ($tabs) use ($_tab_template) {
    $return = array();
    foreach ($tabs as $tab) {
        if ($tab['active']) {
            $return[] = str_replace(
                array('{url}', '{name}'),
                array($tab['url'], $tab['name']),
                $_tab_template['active']
            );
        } else {
            $return[] = str_replace(
                array('{url}', '{name}'),
                array($tab['url'], $tab['name']),
                $_tab_template['inactive']
            );
        }
    }
    return sprintf($_tab_template['container'], implode($_tab_template['delimiter'], $return));
});

$templates->registerFunction('fixBadge', function ($number) {
    if ($number > 99) {
        $number = '99+';
    }
    return $number;
});

if (preg_match('/[a-z]/', $module) && preg_match('/[a-z]/', $module_file)) {
    if ($module_action && preg_match('/[a-z\-]/', $module_action)) {
        $file = 'modules/' . $module . '/' . $module_file . '/' . $module_action . '.php';
    } else {
        $file = 'modules/' . $module . '/' . $module_file . '.php';
    }
    if (file_exists($file)) {
        if (file_exists('modules/' . $module . '/_settings.php')) {
            require_once('modules/' . $module . '/_settings.php');
        }
        if ($module_error) {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $module_error;
        } else {
            require_once($file);
        }
    }
}

if (! defined('HEADER_LOADED')) {
    require('system/header.php');
}

if ($user_id) {
    if (!isset($chat_count)) {
        $chat_count = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_chat`'), 0);
    }
    $unread_chat = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_chat` WHERE `time` > "' . $datauser['chat_read'] . '"'), 0);
    $templates->addData([
        'user' => [
            'id'           => (int) $datauser['id'],
            'account'      => $datauser['account'],
            'name'         => $datauser['imname'],
            'profile_url'  => SITE_PATH . '/profile/' . $datauser['account'] . '.' . $datauser['id'] . '/',
            'avatar_small' => functions::get_avatar($user_id, 1),
            'avatar'       => functions::get_avatar($user_id),
            'cover'        => functions::getCover($user_id),
            'rights'       => isset($user_rights[$rights]) ? $user_rights[$rights] : '',
            'coin'         => (int) $datauser['coin'],
            'gold'         => (int) $datauser['gold'],
            'field_h'      => (int) $set_user['field_h']
        ],
        'unread_message'      => $unread_message,
        'unread_notification' => $unread_notification,
        'unread_chat'      => $unread_chat,
        'chat_count'       => $chat_count
    ]);
}

// add header variables
$templates->addData([
    'meta_tags'           => $meta_tags,
    'html_links'          => $html_links,
    'html_js'             => $html_js,
    'page_title'          => functions::checkout($page_title),
    'headmod'             => $headmod,
    'year'                => date('Y'),
    'ban'                 => $ban
], ['layout', 'layout.simple']);
$templates->addData([
    'device'            => $device,
    'csrf_token'        => CSRF_TOKEN,
    'lang_iso'          => core::$lng_iso,
    'lang'              => $lng,
    'cms_ads'           => $cms_ads,
    'is_ajax'           => IS_AJAX,
    'site_url'          => SITE_URL,
    'site_path'         => SITE_PATH,
    'theme_url'         => THEME_URL,
    'theme_path'        => THEME_PATH,
    'loged'             => $user_id,
    'set'               => [
        'admp'      => $set['admp'],
        'copyright' => functions::checkout($set['copyright']),
        'lang'      => $set['lng']
    ],
    'rights'            => $rights,
    'page'              => $page,
    'breadcrumb'        => $_breadcrumb,
    'show_users_link'   => ($user_id || $set['active']),
    'kmess'             => $kmess
]);

if ($error_rights) {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_rights'];
}

if (!$tpl_file) {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_404'];
}
$content = $templates->render($tpl_file, $tpl_data);

if (IS_AJAX) {
    $ajax_data['page_id']             = $headmod;
    if ($user_id) {
        $ajax_data['coin']                = $datauser['coin'];
        $ajax_data['gold']                = $datauser['gold'];
        $ajax_data['unread_message']      = $unread_message;
        $ajax_data['unread_notification'] = $unread_notification;
        $ajax_data['unread_chat']         = $unread_chat;
        $ajax_data['chat_count']          = $chat_count;
        $ajax_data['update_chat']         = ($datauser['chat_read'] < $set['chat_last']);
    }
    $ajax_data['breadcrumb']          = $_breadcrumb;
    $ajax_data['page_title']          = $page_title;
    $ajax_data['html_content']        = $content;
    header('Content-Type: application/json; Charset=UTF-8');
    header('X-vDevs-Location: ' . trim($_SERVER['REQUEST_URI']));
    echo json_encode($ajax_data); exit;
} else {
    echo $content; exit;
}
