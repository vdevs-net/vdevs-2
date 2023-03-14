<?php
defined('_MRKEN_CMS') or die('Restricted access');


class statistic
{
    private $query_text = FALSE;
    private $http_referer = 'unknow';
    private $stat_user_agent = '';
    private $request_uri = '';
    private $http_site = 'unknow';
    private $stat_ip_via_proxy = 0;
    private $current_data = array();
    public static $hosty = 0;
    public static $hity = 0;
    private $robot = false;
    private $robot_type = false;
    private $new_host = 0; // int
    private $page_title = '';
    public static $system_time = false;
    private $browser = false;
    private $os = false;
        

    function __construct($title = '')
    {
        self::$system_time = time();
        $this->get_data();
        $this->get_query_text();
        $this->get_browser($this->stat_user_agent);
        $this->get_os($this->stat_user_agent);
        self::$hosty = $this->current_data['host'];
        self::$hity = $this->current_data['hity'];
        $_SESSION["host"] = $this->current_data['host'];
        $_SESSION["hity"] = $this->current_data['hity'];
        $this->page_title = isset($title) ? $title : core::$system_set['copyright'];
        $time1 = date("d.m.y", $this->current_data['date']);
        $time2 = date("d.m.y", self::$system_time);
        if ($time1 !== $time2) {
            $this->close_day();
        }
        $this->check_host();
    }

    /*
    -----------------------------------------------------------------
    Сохраняем все данные
    -----------------------------------------------------------------
    */
    function __destruct()
    {
        if ($this->query_text != FALSE) {
            $req = mysql_query("SELECT * FROM `stat_robots` WHERE `query` = '" . mysql_real_escape_string($this->query_text) . "' AND `engine` = '" . mysql_real_escape_string($this->http_site) . "' LIMIT 1");
            if (mysql_num_rows($req)) {
                $quer = mysql_fetch_array($req);
                $time1 = date('d.m.y', $quer['date']);
                $time2 = date('d.m.y', self::$system_time);
                if ($time1 !== $time2) {
                    $today = 1;
                } else {
                    $today = $quer['today'] + 1;
                }
                $count = $quer['count'] + 1;
                mysql_query("UPDATE `stat_robots` SET `date` = '" . self::$system_time .
                    "', `url` = '" . mysql_real_escape_string($this->http_referer) . "', `ua` = '" . mysql_real_escape_string($this->stat_user_agent) .
                    "', `ip` = '" . core::$ip . "', `count` = '" . $count . "', `today` = '" . $today .
                    "' WHERE `query` = '" . mysql_real_escape_string($this->query_text) . "' AND `engine` = '" . mysql_real_escape_string($this->http_site) . "'");
            } else {
                mysql_query("INSERT INTO `stat_robots` SET `engine` = '" . mysql_real_escape_string($this->http_site) .
                    "', `date` = '" . self::$system_time . "', `url` = '" . mysql_real_escape_string($this->http_referer) .
                    "', `query` = '" . mysql_real_escape_string($this->query_text) . "', `ua` = '" . mysql_real_escape_string($this->stat_user_agent) .
                    "', `ip` = '" . core::$ip . "', `count` = '1', `today` = '1'");
            }
        }
        
        $sql = '';
        if ($this->stat_ip_via_proxy)
            $sql = ', `ip_via_proxy` = "' . long2ip($this->stat_ip_via_proxy) . '"';
        if (core::$user_id)
            $sql = ', `user` = "' . core::$user_id . '"';
        if ($this->robot)
            $sql .= ', `robot` = "' . $this->robot . '", `robot_type` = "' . $this->
                robot_type . '"';
                
        $sql .= ', `phone` = "'.$this->browser.'", `os` = "'.$this->os.'"';

        mysql_query("INSERT INTO `counter` SET
            `date` = '" . self::$system_time . "',
            `browser` = '" . mysql_real_escape_string($this->stat_user_agent) . "',
            `ip` = '" . long2ip(core::$ip) . "',
            `ref` = '" . mysql_real_escape_string($this->http_referer) . "',
            `host` = '" . $this->new_host . "',
            `site` = '" . mysql_real_escape_string($this->http_site) . "',
            `pop` = '" . mysql_real_escape_string($this->request_uri) . "',
            `head` = '" . mysql_real_escape_string($this->page_title) . "' " . $sql . ";") or die(mysql_error());;

    }

    /*
    -----------------------------------------------------------------
    Получаем исходные данные
    -----------------------------------------------------------------
    */
    private function get_data()
    {
        $this->stat_user_agent = core::$user_agent;
        $this->stat_ip_via_proxy = core::$ip_via_proxy;

        $request_uri = urldecode(trim($_SERVER['REQUEST_URI']));
        $this->request_uri = strtok($request_uri, '?');
        $this->http_referer = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : $this->http_referer;

        if (isset($_SERVER['HTTP_REFERER'])) {
            $http_site = parse_url($_SERVER['HTTP_REFERER']);
            $this->http_site = isset($http_site['host']) ? functions::checkin($http_site['host']) : $this->http_site;
        }

        $this->current_data = mysql_fetch_array(mysql_query("SELECT MAX(`date`) AS `date`, MAX(`host`) AS `host`, MAX(`hits`) AS `hity` FROM `counter`"));

        $rob_detect = new RobotsDetect($this->stat_user_agent);
        $this->robot = $rob_detect->getNameBot();
        $this->robot_type = $rob_detect->getTypeBot();

    }


    /*
    -----------------------------------------------------------------
    Перекодировка запросов из поисковиков
    -----------------------------------------------------------------
    */
    private function to_utf($zapros)
    {
        if (mb_check_encoding($zapros, 'UTF-8')) {
        } elseif (mb_check_encoding($zapros, 'windows-1251')) {
            $zapros = iconv("windows-1251", "UTF-8", $zapros);
        } elseif (mb_check_encoding($zapros, 'KOI8-R')) {
            $zapros = iconv("KOI8-R", "UTF-8", $zapros);
        }
        return $zapros;
    }

    /*
    -----------------------------------------------------------------
    Получаем браузер юзера
    -----------------------------------------------------------------
    */
    private function get_browser($user_agent)
    {
        if (preg_match("/nokia/i", $user_agent)) {
            $this->browser = 'nokia';
        }elseif (preg_match("/sony/i", $user_agent)) {
            $this->browser = 'sony';
        }elseif (preg_match("/sec/i", $user_agent) || preg_match("/samsung/i", $user_agent)) {
            $this->browser = 'samsung';
        }elseif (preg_match("/lg/i", $user_agent)) {
            $this->browser = 'lg';
        }elseif (preg_match("/benq/i", $user_agent) || preg_match("/SIE-/i", $user_agent)) {
            $this->browser = 'siemens';
        }elseif (preg_match("/mot/i", $user_agent)) {
            $this->browser = 'motorola';
        }elseif (preg_match("/nec/i", $user_agent)) {
            $this->browser = 'nec';
        }elseif (preg_match("/philips/i", $user_agent)) {
            $this->browser = 'philips';
        }elseif (preg_match("/pantech/i", $user_agent)) {
            $this->browser = 'pantech';
        }elseif (preg_match("/sagem/i", $user_agent)) {
            $this->browser = 'sagem';
        }elseif (preg_match("/fly/i", $user_agent)) {
            $this->browser = 'fly';
        }elseif (preg_match("/panasonic/i", $user_agent)) {
            $this->browser = 'panasonic';
        }elseif (preg_match("/opera mini/i", $user_agent)) {
            $this->browser = 'opera mini';
        }elseif (preg_match("/windows/i", $user_agent) || preg_match("/linux/i", $user_agent)) {
            $this->browser = 'computer';
        }else{
            $this->browser = 'other';
        }
    }
    
    /*
    -----------------------------------------------------------------
    Получаем операционную систему
    -----------------------------------------------------------------
    */
    private function get_os($user_agent)
    {
        if (preg_match("/android/i", $user_agent)) {
            $this->os = 'android';
        } elseif (preg_match("/Windows NT 5.1/i", $user_agent)) {
            $this->os = 'winxp';
        } elseif (preg_match("/Windows NT 6.0/i", $user_agent)) {
            $this->os = 'winvista';
        } elseif (preg_match("/Windows NT 6.1/i", $user_agent)) {
            $this->os = 'win7';
        } elseif (preg_match("/macos/i", $user_agent) || preg_match("/macintosh/i", $user_agent)) {
            $this->os = 'macos';
        } elseif (preg_match("/SymbianOS\/9.1/i", $user_agent) || preg_match("/Series60\/3.0/i", $user_agent)) {
            $this->os = 'symbian91';
        } elseif (preg_match("/SymbianOS\/9.2/i", $user_agent) || preg_match("/Series60\/3.1/i", $user_agent)) {
            $this->os = 'symbian92';
        } elseif (preg_match("/SymbianOS\/9.3/i", $user_agent) || preg_match("/Series60\/3.2/i", $user_agent)) {
            $this->os = 'symbian93';
        } elseif (preg_match("/SymbianOS\/9.4/i", $user_agent) || preg_match("/Series60\/5.0/i", $user_agent)) {
            $this->os = 'symbian94';
        } elseif (preg_match("/Symbian\/3/i", $user_agent) || preg_match("/Series60\/5.2/i", $user_agent)) {
            $this->os = 'symbian3';
        } elseif (preg_match("/Series60\/2./i", $user_agent)) {
            $this->os = 'symbian_other';
        } else {
            $this->os = 'other';
        }
    }


    /*
    -----------------------------------------------------------------
    Получаем текст поискового запроса
    -----------------------------------------------------------------
    */
    private function get_query_text()
    {
        $http_ref = str_replace('&amp;', '&', $this->http_referer);
        $url = parse_url($http_ref);
        if(isset($url['host']) && isset($url['query']) && $url['query'] !== null) {
            parse_str($url['query'], $query_text);
            if (preg_match('/google\./i', $url['host']) || preg_match('/bing\./i', $url['host']) || preg_match('/ask\.com/i', $url['host'])) {
                if(isset($query_text['q'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['q']));
                }
            } elseif (preg_match('/yandex\./i', $url['host'])) {
                if(isset($query_text['text'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['text']));
                }
            } elseif (preg_match('/nigma\./i', $url['host'])) {
                if(isset($query_text['s'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['s']));
                }
            } elseif (preg_match('/search\.qip\./i', $url['host']) || preg_match('/rambler\./i', $url['host'])) {
                if(isset($query_text['query'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['query']));
                }
            } elseif (preg_match('/aport\./i', $url['host'])) {
                if(isset($query_text['r'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['r']));
                }
            } elseif (preg_match('/yahoo\./i', $url['host'])) {
                if(isset($query_text['p'])) {
                    $this->query_text = functions::checkin(urldecode($query_text['p']));
                }
            } elseif (preg_match('/mail\.ru/i', $url['host']) || preg_match('/gogo\./i', $url['host'])) {
                if(isset($query_text['q'])) {
                    $this->query_text = functions::checkin($this->to_utf(urldecode($query_text['q'])));
                }
            }
        }
    }


    /*
    -----------------------------------------------------------------
    Проверяем хост
    -----------------------------------------------------------------
    */
    private function check_host()
    {
        if (!isset($_COOKIE['hosty'])) {
            setcookie('hosty', '1', time() + 86400, COOKIE_PATH);

            $sql = ($this->stat_ip_via_proxy) ? " AND `ip_via_proxy` = '" . long2ip($this->stat_ip_via_proxy) . "'" : '';
            $ip = ($this->stat_ip_via_proxy) ? long2ip($this->stat_ip_via_proxy) : long2ip(core::$ip);
            $ip_time = self::$system_time - 900; // The time during which the count 1 ip one user.
            $ip_check = mysql_result(mysql_query("SELECT COUNT(*) FROM `counter` WHERE (`ip` = '" . $ip . "' OR `ip_via_proxy` = '" . $ip . "') AND `date` > '" . $ip_time . "';"), 0);
            if($ip_check == 0){
            $db_check = mysql_result(mysql_query("SELECT COUNT(*) FROM `counter` WHERE `browser` = '" . mysql_real_escape_string($this->stat_user_agent) . "' AND `ip` = '" . long2ip(core::$ip) . "'" . $sql . ";"), 0);
                
            if ($db_check == 0 && !$this->robot)
                $this->new_host = self::$hosty + 1;
            }
        }
    }


    /*
    -----------------------------------------------------------------
    Закрываем прошедший день
    -----------------------------------------------------------------
    */
    private function close_day()
    {
        $where_time = strtotime(date("d F y", self::$system_time));
        $where_time2 = $where_time - 86400;
        $sql = "(SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" . $where_time2 .
            "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%yandex%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%mail%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%rambler%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%google%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%gogo%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%yahoo%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%bing%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%nigma%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%qip%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%aport%') UNION ALL (SELECT COUNT(*) FROM `stat_robots` WHERE `date` > '" .
            $where_time2 . "' AND `date` < '" . $where_time .
            "' AND `engine` LIKE '%ask%')";

        $query = mysql_query($sql);
        $count_query = array();
        while ($result_array = mysql_fetch_array($query)) {
            $count_query[] = $result_array[0];
        }

        mysql_query("insert into `countersall` values('" . $this->current_data['date'] .
            "','" . self::$hity . "','" . self::$hosty . "','" . $count_query[0] . "','" . $count_query[2] .
            "', '" . $count_query[3] . "', '" . $count_query[1] . "', '" . $count_query[4] .
            "', '" . $count_query[5] . "', '" . $count_query[6] . "', '" . $count_query[7] .
            "', '" . $count_query[8] . "', '" . $count_query[9] . "', '" . $count_query[10] . "');");

        mysql_query("TRUNCATE TABLE `counter`;");

        self::$hity = 0;
        self::$hosty = 0;
        $_SESSION["host"] = 0;
        $_SESSION["hity"] = 0;
        setcookie('hosty', '', time() -1, COOKIE_PATH);
    }
}