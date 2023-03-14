<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
// die('Đang cập nhật! Vui lòng chờ ít phút!');
//Error_Reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
ini_set('session.use_trans_sid', '0');
ini_set('arg_separator.output', '&amp;');
date_default_timezone_set('Asia/Ho_Chi_Minh');
mb_internal_encoding('UTF-8');

define('DS', DIRECTORY_SEPARATOR);
// Root dir
define('ROOTPATH', dirname(dirname(__FILE__)) . DS);
// now time
define('SYSTEM_TIME', time());
// Ignore any user abort requests
ignore_user_abort(true);
// Detect AJAX request
$header = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower(trim($_SERVER['HTTP_X_REQUESTED_WITH'])) : '';
define('IS_AJAX', ($header == 'xmlhttprequest'));
unset($header);
$method = isset($_SERVER['REQUEST_METHOD']) ? mb_strtoupper(trim($_SERVER['REQUEST_METHOD'])) : '';
define('IS_POST', ($method === 'POST'));
unset($method);
// load config
require(ROOTPATH . 'system' . DS . 'config.php');
// Autoload class
require(ROOTPATH . 'system' . DS . 'autoload.php');
require(ROOTPATH . 'system' . DS . 'vendor' . DS . 'autoload.php');

// routers
$request_uri = substr(urldecode($_SERVER['REQUEST_URI']), strlen(SITE_PATH . '/'));
$_uri = '';
if (strpos($request_uri, '?') !== false) {
    $_uri = substr($request_uri, strpos($request_uri, '?'));
    $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
}
if (preg_match('#page-([\d]+)$#', $request_uri, $matches)) {
    // We don't want to be overwriting values in $_REQUEST that were set in POST or COOKIE
    if (!isset($_POST['page'])) {
        $_REQUEST['page'] = abs(intval($matches[1]));
    }

    $_GET['page'] = abs(intval($matches[1]));
    $request_uri = preg_replace('#page-([\d]+)$#', '', $request_uri);
}

require_once(ROOTPATH . 'system' . DS . 'configs' . DS . 'rewrite_rules.php');

foreach ($rewrite_rules as $rule => $rewrite_to) {
    if (preg_match($rule, $request_uri, $matches)) {
        if (isset($rewrite_to[1]) && empty($matches[$rewrite_to[1]])) {
            functions::redirect(SITE_URL . '/' . $request_uri . '/' . $_uri, 301);
        }
        $rewritten_url = preg_replace($rule, $rewrite_to[0], $request_uri);
        $url_parts = explode('?', $rewritten_url);
        // If there is a query string
        if (isset($url_parts[1])) {
            $query_string = explode('&', $url_parts[1]);

            // Set $_GET properly for all of the variables
            // We also set $_REQUEST if it's not already set
            foreach ($query_string as $cur_param) {
                $param_data = explode('=', $cur_param);

                // Sometimes, parameters don't set a value (eg: script.php?foo), so we set them to null
                $param_data[1] = isset($param_data[1]) ? $param_data[1] : null;

                // We don't want to be overwriting values in $_REQUEST that were set in POST or COOKIE
                if (!isset($_POST[$param_data[0]]) && !isset($_COOKIE[$param_data[0]])) {
                    $_REQUEST[$param_data[0]] = urldecode($param_data[1]);
                }

                $_GET[$param_data[0]] = urldecode($param_data[1]);
            }
        }
        break;
    }
}
unset($_uri, $request_uri, $rewrite_rules, $rule, $rewrite_to, $rewritten_url, $url_parts, $query_string, $cur_param, $param_data);

// Start system core
new core;

// System variable
$lng = array();
$ip = core::$ip; // IP
$agn = core::$user_agent; // User Agent
$set = core::$system_set; // system settings
$lng = core::$lng; // language
$device = core::$device; // device
$wap = $web = $touch = false;
// device
if($device == 'web') {
    $web = true;
    $_theme = $set['theme_web'];
} elseif($device == 'touch') {
    $touch = true;
    $_theme = $set['theme_touch'];
} else {
    $wap = true;
    $_theme = $set['theme_wap'];
}


$user_rights = array(
	0 => 'Member',
	3 => 'F-Mod',
	5 => 'L-Mod',
	6 => 'Super Mod',
	7 => 'Admin',
	9 => 'Trùm!'
);
// Custom variable
$user_id = core::$user_id; // User ID
$rights = core::$user_rights; // User Rights
$datauser = core::$user_data; // all data of user
$set_user = core::$user_set; // user settings
$ban = core::$user_ban; // Ban
$login = isset($datauser['account']) ? $datauser['account'] : false;
$kmess = $set_user['kmess'] > 4 && $set_user['kmess'] < 100 ? $set_user['kmess'] : 10;

$folders = glob(ROOTPATH . 'themes/*/index.php');
foreach ($folders as $val) {
    $val = explode('/', dirname($val));
    $theme_list[] = array_pop($val);
}

$set_user['theme'] = isset($set_user['theme']) && in_array($set_user['theme'], $theme_list) ? $set_user['theme'] : (in_array($_theme, $theme_list) ? $_theme : reset($theme_list));

define('THEME_URL', SITE_URL . '/themes/' . $set_user['theme']);
define('THEME_PATH', SITE_PATH . '/themes/' . $set_user['theme']);
define('THEME_DIR', ROOTPATH . 'themes' . DS . $set_user['theme'] . DS);
define('TEMPLATE_DIR', ROOTPATH . 'themes' . DS . $set_user['theme'] . DS . 'templates');
unset($_theme, $folders, $val);
// load theme misc template
require(THEME_DIR . '_misc_template.php');

if ($user_id) {
    // simple csrf_token
    $_token_fields = array(
        $user_id,
        $agn,
        SALT
    );
    define('CSRF_TOKEN', md5(implode('-', $_token_fields)));
    $_token_valid = false;
    if (isset($_POST['csrf_token'])) {
        $_csrf_token = trim($_POST['csrf_token']);
        if (mb_strlen($_csrf_token) > 4
            && $_csrf_token === CSRF_TOKEN
        ) {
            $_token_valid = true;
        }
    }
    define('TOKEN_VALID', $_token_valid);
    unset($_token_valid, $_token_fields, $_csrf_token);
} else {
    define('CSRF_TOKEN', '');
    define('TOKEN_VALID', false);
}

function validate_referer()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
    $referer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
    if ($referer) {
        $ref = parse_url($referer);
        if ($_SERVER['HTTP_HOST'] === $ref['host']) return;
    }
    die('Invalid request');
}

if ($rights) {
    validate_referer();
}

$prefixs = array(
	0 => $lng['prefix_0'],
	1 => $lng['prefix_1'],
	2 => $lng['prefix_2'],
	3 => $lng['prefix_3'],
	4 => $lng['prefix_4'],
	5 => $lng['prefix_5'],
	6 => $lng['prefix_6'],
	7 => $lng['prefix_7'],
	8 => $lng['prefix_8']
);

$module = isset($_GET['module']) ? trim($_GET['module']) : 'home';
$module_file = isset($_GET['module_file']) ? trim($_GET['module_file']) : $module;
$module_action = isset($_GET['module_action']) ? trim($_GET['module_action']) : '';

// Request variable
$id = isset($_REQUEST['id']) ? abs(intval($_REQUEST['id'])) : false;
$user = isset($_REQUEST['user']) ? abs(intval($_REQUEST['user'])) : false;
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$mod = isset($_REQUEST['mod']) ? trim($_REQUEST['mod']) : '';
$do = isset($_REQUEST['do']) ? trim($_REQUEST['do']) : false;
$page = isset($_REQUEST['page']) && $_REQUEST['page'] > 0 ? intval($_REQUEST['page']) : 1;
$start = isset($_REQUEST['page']) ? $page * $kmess - $kmess : (isset($_GET['start']) ? abs(intval($_GET['start'])) : 0);

// predefine variables

$meta_tags = $handle_meta_tags = $html_links = $html_js = $tpl_data = $ajax_data = array();
$page_title = $meta_key = $meta_desc = $headmod = $_breadcrumb = $tpl_file = $module_error = '';
$error_rights = false;
if (IS_AJAX) {
    $ajax_data['status'] = 200;
    $ajax_data['success'] = true;
}

// Redirect of site is closed
if (($set['site_access'] == 0 || $set['site_access'] == 1) && $module != 'login' && !$user_id) {
    functions::closeSite(); exit();
}
// load theme settings
require(THEME_DIR . '_settings.php');

// output buffering
if ($set['gzip'] && extension_loaded('zlib')) {
    ini_set('zlib.output_compression_level', 3);
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

define('RIGHTS_SUPER_ADMIN', 9);
define('RIGHTS_ADMIN', 7);
define('RIGHTS_SUPER_MODER', 6);
define('RIGHTS_MODER_FORUM', 3);
