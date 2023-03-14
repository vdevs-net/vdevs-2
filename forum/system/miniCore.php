<?php
defined('_MRKEN_CMS') or die('Restricted access');
ini_set('display_errors', 'Off');
ini_set('session.use_trans_sid', '0');
ini_set('arg_separator.output', '&amp;');
date_default_timezone_set('Asia/Ho_Chi_Minh');
mb_internal_encoding('UTF-8');

class core
{
    public static $ip; // Путь к корневой папке
    public static $ip_via_proxy = 0; // IP адрес за прокси-сервером
    public static $ip_count = array(); // Счетчик обращений с IP адреса

    private $flood_chk = 1; // Enabling - Disabling IP in flood
    private $flood_interval = '60'; // The time interval in seconds
    private $flood_limit = '120'; // The number of requests allowed per interval

    function __construct()
    {
        // Получаем IP адреса
        $ip = ip2long($_SERVER['REMOTE_ADDR']) or die('Invalid IP');
        self::$ip = sprintf('%u', $ip);

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $vars)) {
            foreach ($vars[0] AS $var) {
                $ip_via_proxy = ip2long($var);
                if ($ip_via_proxy && $ip_via_proxy != $ip && !preg_match('#^(10|172\.16|192\.168)\.#', $var)) {
                    self::$ip_via_proxy = sprintf('%u', $ip_via_proxy);
                    break;
                }
            }
        }

        $this->ip_flood(); // Проверка адреса IP на флуд
        $this->db_connect(); // Соединяемся с базой данных
    }

    /*
    -----------------------------------------------------------------
    Подключаемся к базе данных
    -----------------------------------------------------------------
    */
    private function db_connect()
    {
        require(ROOTPATH . 'system/config.php');
        $connect = @mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Error: cannot connect to database server');
        @mysql_select_db(DB_NAME) or die('Error: specified database does not exist');
        @mysql_query('SET NAMES "utf8"', $connect);
    }

    /*
    -----------------------------------------------------------------
    Проверка адреса IP на флуд
    -----------------------------------------------------------------
    */
    private function ip_flood()
    {
        if ($this->flood_chk) {
            //if ($this->ip_whitelist(self::$ip))
            //    return true;
            $file = ROOTPATH . 'files/system/cache/ip_flood.dat';
            $tmp = array();
            $requests = 1;
            if (!file_exists($file)) $in = fopen($file, 'w+');
            else $in = fopen($file, 'r+');
            flock($in, LOCK_EX) or die('Cannot flock ANTIFLOOD file.');
            $now = time();
            while ($block = fread($in, 8)) {
                $arr = unpack('Lip/Ltime', $block);
                if (($now - $arr['time']) > $this->flood_interval) continue;
                if ($arr['ip'] == self::$ip) $requests++;
                $tmp[] = $arr;
                self::$ip_count[] = $arr['ip'];
            }
            fseek($in, 0);
            ftruncate($in, 0);
            for ($i = 0; $i < count($tmp); $i++) fwrite($in, pack('LL', $tmp[$i]['ip'], $tmp[$i]['time']));
            fwrite($in, pack('LL', self::$ip, $now));
            fclose($in);
            if ($requests > $this->flood_limit) {
                die('FLOOD: exceeded limit of allowed requests');
            }
        }
    }

}
function bodau($text){
    if(empty($text)) {
        return false;
    }
    $text = html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
    $text = str_replace('́', '', $text);
    $text = str_replace('̀', '', $text);
    $text = str_replace('̃', '', $text);
    $text = str_replace('̣', '', $text);
    $text = str_replace('̉', '', $text);
    $text = mb_strtolower($text);
    $text = preg_replace('/(à|á|ả|ã|ạ|â|ầ|ấ|ẩ|ẫ|ậ|ă|ằ|ắ|ẳ|ẵ|ặ)/','a', $text);
    $text = preg_replace('/(è|é|ẻ|ẽ|ẹ|ê|ề|ế|ể|ễ|ệ)/','e', $text);
    $text = preg_replace('/(ì|í|ỉ|ĩ|ị)/', 'i', $text);
    $text = preg_replace('/(ò|ó|ỏ|õ|ọ|ô|ồ|ố|ổ|ỗ|ộ|ơ|ờ|ớ|ở|ỡ|ợ)/', 'o', $text);
    $text = preg_replace('/(ù|ú|ủ|ũ|ụ|ư|ừ|ứ|ử|ữ|ự)/', 'u', $text);
    $text = preg_replace('/(ỳ|ý|ỷ|ỹ|ỵ)/', 'y', $text);
    $text = preg_replace('/(đ|đ)/', 'd', $text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+-/', '-', $text);
    $text = preg_replace('/^-/', '', $text);
    $text = preg_replace('/-$/', '', $text);
    return $text;
}

// Root dir
define('ROOTPATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

// Start system core
new core;

// Request variable
$id = isset($_REQUEST['id']) ? abs(intval($_REQUEST['id'])) : false;
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$mod = isset($_REQUEST['mod']) ? trim($_REQUEST['mod']) : '';

// output buffering
if (@extension_loaded('zlib')) {
    ini_set('zlib.output_compression_level', 3);
    ob_start('ob_gzhandler');
} else {
    ob_start();
}