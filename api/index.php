<?php
define('_MRKEN_CMS', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
ini_set('session.use_trans_sid', '0');
ini_set('arg_separator.output', '&amp;');
date_default_timezone_set('Asia/Ho_Chi_Minh');
mb_internal_encoding('UTF-8');

define('SYSTEM_TIME', time());
// Ignore any user abort requests
ignore_user_abort(true);

require('../forum/system/config.php');

session_name('SESID');
session_start();

class antiFlood
{
    private $ip; // Путь к корневой папке

    private $flood_chk = 1; // Enabling - Disabling IP in flood
    private $flood_interval = 60; // The time interval in seconds
    private $flood_limit = 120; // The number of requests allowed per interval

    function __construct()
    {
        // Получаем IP адреса
        $ip = ip2long($_SERVER['REMOTE_ADDR']) or die('Invalid IP');
        $this->ip = sprintf('%u', $ip);

        $this->ip_flood(); // Проверка адреса IP на флуд
    }

    private function ip_flood()
    {
        if ($this->flood_chk) {
            $file = '../forum/files/system/cache/ip_flood.dat';
            $tmp = array();
            $requests = 1;
            if (!file_exists($file)) {
                $in = fopen($file, 'w+');
            } else {
                $in = fopen($file, 'r+');
            }
            flock($in, LOCK_EX) or die('Cannot flock ANTIFLOOD file.');
            while ($block = fread($in, 8)) {
                $arr = unpack('Lip/Ltime', $block);
                if ((SYSTEM_TIME - $arr['time']) > $this->flood_interval) {
                    continue;
                }
                if ($arr['ip'] == $this->ip) {
                    $requests++;
                }
                $tmp[] = $arr;
            }
            fseek($in, 0);
            ftruncate($in, 0);
            for ($i = 0; $i < count($tmp); $i++) {
                fwrite($in, pack('LL', $tmp[$i]['ip'], $tmp[$i]['time']));
            }
            fwrite($in, pack('LL', $this->ip, SYSTEM_TIME));
            fclose($in);
            if ($requests > $this->flood_limit) {
                die('FLOOD: exceeded limit of allowed requests');
            }
        }
    }
}

class db
{
    private $connect;

    function __construct()
    {
        $db_host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $db_user = defined('DB_USER') ? DB_USER : '';
        $db_pass = defined('DB_PASS') ? DB_PASS : '';
        $db_name = defined('DB_NAME') ? DB_NAME : '';
        $this->connect = @mysql_connect($db_host, $db_user, $db_pass) or die('Error: cannot connect to database server');
        @mysql_select_db($db_name) or die('Error: specified database does not exist');
        $this->query('SET NAMES "utf8"', $this->connect);
    }

    public function query($sql) {
        return mysql_query($sql);
    }
    public function num_rows($res) {
        return mysql_num_rows($res);
    }
    public function fetch_assoc($res) {
        return mysql_fetch_assoc($res);
    }
    public function result($res, $row, $field = 0) {
        return mysql_result($res, $row, $field); 
    }
}
new antiFlood();
$db = new db();
if (get_magic_quotes_gpc()) {
    $in = array(
        &$_GET,
        &$_POST,
        &$_REQUEST,
        &$_COOKIE
    );
    while ((list($k, $v) = each($in)) !== false) {
        foreach ($v as $key => $val) {
            if (!is_array($val)) {
                $in[$k][$key] = stripslashes($val);
                continue;
            }
            $in[] = &$in[$k][$key];
        }
    }
    unset($in);
    if (!empty($_FILES)) {
        foreach ($_FILES as $k => $v) {
            $_FILES[$k]['name'] = stripslashes((string)$v['name']);
        }
    }
}


// output buffering
if (extension_loaded('zlib')) {
    ini_set('zlib.output_compression_level', 3);
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

$ajax_data['status'] = 204;

// header('Access-Control-Allow-Origin: ' . SITE_SCHEME . SITE_HOST);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; Charset=UTF-8');

$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$token = isset($_REQUEST['token']) ? trim($_REQUEST['token']) : '';
$time = isset($_REQUEST['time']) ? abs(intval($_REQUEST['time'])) : '';
$args = explode('-', $token);

$mods = [
    'interval' => 60
];

if ($act && $args[0] && isset($args[1]) && array_key_exists($act, $mods)) {
    set_time_limit($mods[$act]);
    $req = $db->query('SELECT `browser`, `chat_read` FROM `users` WHERE `id` = "' . $args[0] . '" LIMIT 1');
    if ($db->num_rows($req)) {
        $user = $db->fetch_assoc($req);
        $user['id'] = $args[0];
        if (md5(implode('-', array($user['id'], $user['browser'], SALT))) == $args[1]) {
            require('includes/' . $act . '.php');
        }
    }
}
