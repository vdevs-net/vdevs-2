<?php
defined('_MRKEN_CMS') or die('Restricted access');

class functions extends core
{
    private static $avatars = array();

    public static function get_avatar($user_id, $small = false) {
        $size = (core::$device == 'wap' || $small ? '_small' : '');
        if (!isset(self::$avatars[$user_id])) {
            if (file_exists(ROOTPATH . 'files/users/avatar/' . $user_id . '_small.png')) {
                $filemtime = filemtime(ROOTPATH . 'files/users/avatar/' . $user_id . '_small.png');
                self::$avatars[$user_id] = $filemtime;
            } else {
                self::$avatars[$user_id] = 0;
            }
        }
        if (self::$avatars[$user_id]) {
            return SITE_PATH . '/files/users/avatar/' . $user_id . $size . '.png?' . self::$avatars[$user_id];
        } else {
            return SITE_PATH . '/assets/images/noavatar' . $size . '.png';
        }
    }

    public static function getCover($user_id, $small = false) {
        $size = (core::$device == 'wap' || $small ? '_small' : '');
        if (file_exists(ROOTPATH . 'files/users/cover/' . $user_id . '_small.jpg')) {
            $filemtime = filemtime(ROOTPATH . 'files/users/cover/' . $user_id . '_small.jpg');
            return SITE_PATH . '/files/users/cover/' . $user_id . $size . '.jpg?' . $filemtime;
        } else {
            return SITE_PATH . '/assets/images/nocover' . $size . '.jpg';
        }
    }

    public static function mail($title, $body, $to_email, $to_name = ''){
        $mail = new PHPMailer(); // Khai báo tạo PHPMailer
        $mail->IsSMTP(); //Khai báo gửi mail bằng SMTP
		$mail->SetFrom(SMTP_SEND_FROM, core::$system_set['copyright']); // Thông tin người gửi
		$mail->AddReplyTo(SMTP_REPLY_TO, core::$system_set['copyright']); // Ấn định email sẽ nhận khi người dùng reply lại.
		//Tắt mở kiểm tra lỗi trả về, chấp nhận các giá trị 0 1 2
		// 0 = off không thông báo bất kì gì, tốt nhất nên dùng khi đã hoàn thành.
		// 1 = Thông báo lỗi ở client
		// 2 = Thông báo lỗi cả client và lỗi ở server
		$mail->SMTPDebug   = 0;
		$mail->Debugoutput = 'html'; // Lỗi trả về hiển thị với cấu trúc HTML
		$mail->Host        = 'smtp.gmail.com'; //host smtp để gửi mail
		$mail->Port        = 587; // cổng để gửi mail
		$mail->SMTPSecure  = 'tls'; //Phương thức mã hóa thư - ssl hoặc tls
		$mail->SMTPAuth    = true; //Xác thực SMTP
		$mail->Username    = SMTP_USER; // Tên đăng nhập tài khoản Gmail
		$mail->Password    = SMTP_PASSWORD; //Mật khẩu của gmail
		$mail->CharSet     = 'UTF-8';
		$mail->Subject     = $title; //Tiêu đề của thư
		$mail->AltBody     = 'Thư gửi từ MXH Phố Nhỏ'; //Nội dung rút gọn hiển thị bên ngoài thư mục thư.
		$mail->AddAddress($to_email, $to_name); //Email của người nhận
		$mail->MsgHTML($body); //Nội dung của bức thư.
		//$mail->AddAttachment("images/demo.png");//Tập tin cần attach
		//Tiến hành gửi email và kiểm tra lỗi
		if($mail->Send()) {
			return true;
		} else {
			return $mail->ErrorInfo;
		}
	}

    /**
     * Аntiflood
     * Mode:
     *   1 - adaptive
     *   2 - Day / Night
     *   3 - Day
     *   4 - Night
     *
     * @return int|bool
     */
    public static function antiflood()
    {
        $default = array(
            'mode' => 2,
            'day' => 5,
            'night' => 15,
            'dayfrom' => 10,
            'dayto' => 22
        );
        $af = isset(self::$system_set['antiflood']) ? unserialize(self::$system_set['antiflood']) : $default;
        switch ($af['mode']) {
            case 1:
                // Adaptive mode
                $adm = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `rights` > 0 AND `lastdate` > " . (SYSTEM_TIME - 300)), 0);
                $limit = $adm > 0 ? $af['day'] : $af['night'];
                break;
            case 3:
                // Day
                $limit = $af['day'];
                break;
            case 4:
                // Night
                $limit = $af['night'];
                break;
            default:
                // Default day / night
                $c_time = date('G', SYSTEM_TIME);
                $limit = $c_time > $af['day'] && $c_time < $af['night'] ? $af['day'] : $af['night'];
        }
        if (self::$user_rights > 0) {
            $limit = 4; // For set Administration limit of 4 seconds
        }
        $flood = self::$user_data['lastpost'] + $limit - SYSTEM_TIME;
        if ($flood > 0)
            return $flood;
        else
            return FALSE;
    }

	public static function forum_tags ($text) {
		if(empty($text)) {
            return '';
        }
		$return = array();
		$tags = array_map('trim', explode(',', $text));
		foreach ($tags as $tag) {
			if(!empty($tag) && mb_strlen($tag) > 3) {
				$return[] = $tag;
            }
		}
        if (empty($return)) {
            return '';
        }
		$return = array_slice($return, 0, 5);
		return serialize($return);
	}
	/*
	* Show tags
	* $text str
	* $mod
	*    0 - return text of tags
	*    1 - return search tags
	*/
	public static function show_tags($text, $mod = 0)
    {
        global $_tag_template;
		if (!function_exists('search_link')) {
			function search_link($text) {
                global $_tag_template;
                return strtr($_tag_template['content'],
                array(
                        '{url}' => SITE_URL . '/forum/search?search=' . urlencode($text) . '&t=1',
                        '{name}' => functions::checkout($text)
                    )
                );
			}
		}
		$tags = unserialize($text);
		if($mod == 1){
			$tags = array_map('search_link', $tags);
            return str_replace('{TAGS}', implode(' ', $tags), $_tag_template['container']);
		} else {
            $tags = array_map('self::checkout', $tags);
            return implode(', ', $tags);
        }
	}

	/* random generator */
	public static function rand_code($length) {
		$vals = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$result = '';
        $vals_len = mb_strlen($vals) - 1;
		for ($i = 1; $i <= $length; $i++) {
			$result .= $vals{rand(0, $vals_len)};
		}
		return $result;
	}

    /**
     * Фильтрация строк
     *
     * @param string $str
     *
     * @return string
     */
    public static function checkin($str, $remove_uft8mb4 = false)
    {
        $str = trim($str);
        if (empty($str)) {
            return $str;
        }
        if (function_exists('iconv')) {
            if (mb_check_encoding($str, 'UTF-8')) {
            } elseif (mb_check_encoding($str, 'windows-1251')) {
                $str = iconv('windows-1251', 'UTF-8', $str);
            }
        }

        if ($remove_uft8mb4) {
            $str = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $str);
        }

        // Filter the invisible characters
        $str = preg_replace('/[^\P{C}\n]+/u', '', $str);

        return trim($str);
    }

    /**
     * text processing before displaying
     *
     * @param string $str
     * @param int $br      Parameter handling line breaks
     *                        0 - not process (default)
     *                        1 - process
     *                        2 - instead of line breaks inserted blanks
     *                        3 - process with paragraph
     * @param int $tags    Parameter tag processing
     *                        0 - not process (default)
     *                        1 - process
     *                        2 - cut tags
     * @param int $smileys Parameter smiley processing
     *                        0 - not process (default)
     *                        1 - process with user settings
     *                        2 - process without user settings
     *
     * @return string
     */
    public static function checkout($str, $br = 0, $tags = 0, $smileys = 0)
    {
        $str = htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
        $smileys = (($smileys == 1 && self::$user_set['smileys']) || $smileys == 2);

        if ($br == 1) {
            // Insert line breaks
            $str = nl2br($str);
        } elseif ($br == 2) {
            $str = preg_replace('/([\r\n]|\r\n)/is', ' ', $str);
        } elseif ($br == 3) {
            $str = preg_replace('/([\r\n]|\r\n)/is', '</p><p>' . "\r\n", $str);
            $str = '<p>' . $str . '</p>';
        }

        if ($tags == 1) {
            $str = bbcode::tags($str, $smileys);
        } elseif ($tags == 2) {
            $str = bbcode::notags($str, $smileys);
        } elseif ($smileys) {
            $str = bbcode::process_emoticons($str);
        }

        if ($br == 3) {
            $str = str_replace('<p></p><div', '<div', $str);
            $str = str_replace('div><p></p>', 'div>', $str);
        } else {
            $str = str_replace('<p>', '', $str);
            $str = str_replace('</p>', '', $str);
        }

        return trim($str);
    }

    /**
     * Показываем дату с учетом сдвига времени
     *
     * @param int $var Время в Unix формате
     *
     * @return string Отформатированное время
     */
    public static function display_date($var)
    {
        if (date('Y', $var) == date('Y', SYSTEM_TIME)) {
            if (date('z', $var) == date('z', SYSTEM_TIME))
                return self::$lng['today'] . ', ' . date('H:i', $var);
            if (date('z', $var) == date('z', SYSTEM_TIME) - 1)
                return self::$lng['yesterday'] . ', ' . date('H:i', $var);
        }

        return date('d.m.Y / H:i', $var);
    }

    /**
     * Сообщения об ошибках
     *
     * @param string|array $error Сообщение об ошибке (или массив с сообщениями)
     * @param string $link  Необязательная ссылка перехода
     *
     * @return bool|string
     */
    public static function display_error($error = '', $link = '')
    {
        if (!empty($error)) {
            $return = '';
            if (is_array($error)) {
                if (count($error) > 1) {
                    $return = '- ' . implode('<br />- ', $error);
                } else {
                    $return = implode('', $error);
                }
            } else {
                $return = $error;
            }
            return '<p>' . $return . '</p>' . (!empty($link) ? '<p>' . $link . '</p>' : '');
        } else {
            return FALSE;
        }
    }

    /**
     * Отображение различных меню
     *
     * @param array $val
     * @param string $delimiter Разделитель между пунктами
     * @param string $end_space Выводится в конце
     *
     * @return string
     */
    public static function display_menu($val = array(), $delimiter = ' | ', $end_space = '')
    {
        return implode($delimiter, array_diff($val, array(''))) . $end_space;
    }

    public static function displayMenu($items = array(), $template = '', $delimiter = ' | ')
    {
        $out = array();
        foreach ($items as $item)
        {
            $out[] = strtr($template, array(
                '{item}' => ($item['url'] ? '<a href="' . functions::checkout($item['url']) . '">' . ($item['text'] ? functions::checkout($item['text']) : functions::checkout($item['url'])) . '</a>' : functions::checkout($item['text']))
            ));
        }
        return implode($delimiter, $out);
    }
	/**
	* unmark
	**/
	public static function unSign($text)
    {
		if (empty($text)) {
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

		return $text;
	}
    /**
    * make unSign URL
    **/
    public static function makeUrl($text)
    {
        if(empty($text)) {
            return false;
        }

        $text = self::unSign($text);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        $text = preg_replace('/-{2,}/', '-', $text);
        $text = preg_replace('/^-|-$/', '', $text);
        if (empty($text)) {
            return 'empty';
        }
        return $text;
    }
    /**
     * Постраничная навигация
     * За основу взята доработанная функция от форума SMF 2.x.x
     *
     * @param string $url
     * @param int $start
     * @param int $total
     * @param int $kmess
     *
     * @return string
     */
    public static function display_pagination($url, $start, $total, $kmess, $suffix = '')
    {
        global $_pagination_template;

        $neighbors = 2;
        if ($start >= $total) {
            $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
        } else {
            $start = max(0, (int)$start - ((int)$start % (int)$kmess));
        }
        $tmpMaxPages = floor(($total - 1) / $kmess) * $kmess;
        $base_link = strtr($_pagination_template['base_link'],
            array(
                '{URL}'    => strtr($url, array('%' => '%%')),
                '{SUFFIX}' => $suffix
            )
        );
        if ($start == 0) {
            $out[] = sprintf($_pagination_template['disabled'], '&laquo;&laquo;');
            $out[] = sprintf($_pagination_template['disabled'], '&laquo;');
        } else {
            $out[] = sprintf($base_link, 1, '&laquo;&laquo;');
            $out[] = sprintf($base_link, $start / $kmess, '&laquo;');
        }
        for ($nCont = 2 * $neighbors; $nCont > $neighbors; $nCont--) {
            if ($start >= $kmess * $nCont && ($start + (2 * $neighbors + 1 - $nCont) * $kmess > $tmpMaxPages)) {
                $tmpStart = $start - $kmess * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
            }
        }
        for ($nCont = $neighbors; $nCont >= 1; $nCont--) {
            if ($start >= $kmess * $nCont) {
                $tmpStart = $start - $kmess * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
            }
        }
        $out[] = sprintf($_pagination_template['current'], ($start / $kmess + 1));
        for ($nCont = 1; $nCont <= $neighbors; $nCont++) {
            if ($start + $kmess * $nCont <= $tmpMaxPages) {
                $tmpStart = $start + $kmess * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
            }
        }
        for ($nCont = $neighbors + 1; $nCont <= 2 * $neighbors; $nCont++) {
            if ($start + $kmess * $nCont <= $tmpMaxPages && ($start - (2 * $neighbors + 1 - $nCont) * $kmess < 0)) {
                $tmpStart = $start + $kmess * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
            }
        }
        if ($start + $kmess < $total) {
            $display_page = ($start / $kmess + 2);
            $out[] = sprintf($base_link, ($start / $kmess + 2), '&raquo;');
            $out[] = sprintf($base_link, ($tmpMaxPages / $kmess + 1), '&raquo;&raquo;');
        } else {
            $out[] = sprintf($_pagination_template['disabled'], '&raquo;');
            $out[] = sprintf($_pagination_template['disabled'], '&raquo;&raquo;');
        }

        return str_replace('{PAGINATION}', implode(' ', $out), $_pagination_template['container']);
    }

    /**
     * Показываем местоположение пользователя
     *
     * @param int $user_id
     * @param string $place
     *
     * @return mixed|string
     */
    public static function display_place($user_id = 0, $place = '')
    {
        global $headmod, $lng;
        $place = explode('-', $place);
        $placelist = $lng['places'];
        if (array_key_exists($place[0], $placelist)) {
            if ($place[0] == 'online' && $headmod == 'online') {
                return $placelist['here'];
            } else {
                return $placelist[$place[0]];
            }
        }

        return '<a href="' . SITE_URL . '">' . $placelist['homepage'] . '</a>';
    }

    /**
     * Отображения личных данных пользователя
     *
     * @param int $user Массив запроса в таблицу `users`
     * @param array $arg  Массив параметров отображения
     *                    [lastvisit] (boolean)   Дата и время последнего визита
     *                    [stshide]   (boolean)   Скрыть статус (если есть)
     *                    [iphide]    (boolean)   Скрыть (не показывать) IP и UserAgent
     *                    [iphist]    (boolean)   Показывать ссылку на историю IP
     *
     *                    [header]    (string)    Текст в строке после Ника пользователя
     *                    [body]      (string)    Основной текст, под ником пользователя
     *                    [sub]       (string)    Строка выводится вверху области "sub"
     *                    [footer]    (string)    Строка выводится внизу области "sub"
     *
     * @return string
     */
    public static function display_user($user = 0, $arg = array())
    {
        global $mod;
        $out = FALSE;

        if (!$user['id']) {
            $out = '<div><b>' . self::$lng['guest'] . '</b>';
            if (!empty($user['account'])) {
                $out .= ': ' . $user['account'];
            }
            $out .= '</div>';
            if (!empty($arg['header'])) {
                $out .= '<div>' . $arg['header'] . '</div>';
            }
        } else {
            $rights = isset($user['rights']) ? $user['rights'] : 0;
            $out .= '<table cellpadding="0" cellspacing="0" width="100%"><tr valign="top"><td width="38"><img src="' . self::get_avatar($user['id']) . '" width="32" height="32" alt="" /></td><td>';

            $out .= '<div><a href="' . SITE_URL . '/profile/' . $user['account'] . '.' . $user['id'] . '/" class="user_' . $rights . '"><b>' . $user['account'] . '</b></a>';
            $rank = array(
                0 => 'Thành viên',
                3 => 'F-Mod',
                6 => 'Super Mod',
                7 => 'Admin',
                9 => 'Trùm!'
            );
            if(!isset($arg['ofhide'])) {
                $out .= ' <img src="' . SITE_URL . '/assets/images/o'.(SYSTEM_TIME > $user['lastdate'] + 300 ? 'ff' : 'n').'.gif" alt="*"/>';
            }
            $out .= '</div>';
            if (!empty($arg['header'])) {
                $out .= '<div>' . $arg['header'] . '</div>';
            }
            $out .= '</td><td align="right"><div>' . $rank[$rights] . '</div>' . ((!isset($arg['stshide']) && !empty($user['status'])) ? '<div class="status">' . self::checkout($user['status']) . '</div>' : '').'</td></tr></table>';
        }
        if (isset($arg['body'])){
            $out .= '<div>' . $arg['body'] . '</div>';
        }
        $ipinf = !isset($arg['iphide']) && self::$user_rights ? 1 : 0;
        $lastvisit = SYSTEM_TIME > $user['lastdate'] + 300 && isset($arg['lastvisit']) ? self::display_date($user['lastdate']) : FALSE;
        if ($ipinf || $lastvisit || isset($arg['sub']) && !empty($arg['sub']) || isset($arg['footer'])) {
            $out .= '<div class="sub">';
            if (isset($arg['sub'])) {
                $out .= '<div>' . $arg['sub'] . '</div>';
            }
            if ($lastvisit) {
                $out .= '<div><span class="gray">' . self::$lng['last_visit'] . ':</span> ' . $lastvisit . '</div>';
            }
            if ($ipinf) {
                $out .= '<div><span class="gray">' . self::$lng['browser'] . ':</span> ' . self::checkout($user['browser']) . '</div>' .
                    '<div><span class="gray">' . self::$lng['ip_address'] . ':</span> ';
                $hist = $mod == 'history' ? '&mod=history' : '';
                $ip = long2ip($user['ip']);
                if (self::$user_rights && isset($user['ip_via_proxy']) && $user['ip_via_proxy']) {
                    $out .= '<b class="red"><a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/search-ip?ip=' . $ip . $hist . '">' . $ip . '</a></b>';
                    $out .= '&#160;[<a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/ip-whois?ip=' . $ip . '">?</a>]';
                    $out .= ' / ';
                    $out .= '<a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/search-ip?ip=' . long2ip($user['ip_via_proxy']) . $hist . '">' . long2ip($user['ip_via_proxy']) . '</a>';
                    $out .= '&#160;[<a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/ip-whois?ip=' . long2ip($user['ip_via_proxy']) . '">?</a>]';
                } elseif (self::$user_rights) {
                    $out .= '<a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/search-ip?ip=' . $ip . $hist . '">' . $ip . '</a>';
                    $out .= '&#160;[<a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/ip-whois?ip=' . $ip . '">?</a>]';
                } else {
                    $out .= $ip;
                }
                if (isset($arg['iphist'])) {
                    $iptotal = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_users_iphistory` WHERE `user_id` = '" . $user['id'] . "'"), 0);
                    // todo: view ip in admin panel
                    $out .= '<div><span class="gray">' . self::$lng['ip_history'] . ':</span> <a href="' . SITE_URL . '/' . self::$system_set['admp'] . '/usr?id=' . $user['id'] . '&mod=ip">[' . $iptotal . ']</a></div>';
                }
                $out .= '</div>';
            }
            if (isset($arg['footer'])) {
                $out .= $arg['footer'];
            }
            $out .= '</div>';
        }

        return $out;
    }

    /**
     * Форматирование имени файла
     *
     * @param string $name
     *
     * @return string
     */
    public static function format($name)
    {
        $f1 = strrpos($name, '.');
        $f2 = substr($name, $f1 + 1, 999);
        $fname = strtolower($f2);

        return $fname;
    }

    /**
     * Получаем данные пользователя
     *
     * @param int $id Идентификатор пользователя
     *
     * @return array|bool
     */
    public static function get_user($id = 0)
    {
        if ($id && $id != self::$user_id) {
            $req = mysql_query('SELECT * FROM `users` WHERE `id` = "' . $id . '" LIMIT 1');
            if (mysql_num_rows($req)) {
                return mysql_fetch_assoc($req);
            } else {
                return FALSE;
            }
        } else {
            return self::$user_data;
        }
    }

    /*
    -----------------------------------------------------------------
    Функция пересчета на дни, или часы
    -----------------------------------------------------------------
    */
    public static function timecount($var)
    {
        global $lng;
        if ($var < 0) {
            $var = 0;
        }
        $day = ceil($var / 86400);
        if ($var > 345600) return $day . ' ' . $lng['timecount_days'];
        if ($var >= 172800) return $day . ' ' . $lng['timecount_days_r'];
        if ($var >= 86400) return '1 ' . $lng['timecount_day'];

        return date('G:i:s', mktime(0, 0, $var));
    }
    public static function forum_link($m)
    {
        global $lng;
        if (isset($m[3])) {
            $p = parse_url($m[3]);
            if ((('http://' . $p['host'] == SITE_HOST || 'https://' . $p['host'] == SITE_HOST) && isset($p['path']) && preg_match('#' . SITE_PATH . '/forum/threads/[a-z0-9-]+\.(\d+)/(page-(\d+))?#', $p['path'], $matches))) {
                $req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id`= "' . $matches[1] . '" AND `thread_deleted` = "0" LIMIT 1');
                if (mysql_num_rows($req) > 0) {
                    $res = mysql_fetch_assoc($req);
                    if (mb_strlen($res['text']) > 63) {
                       $res['text'] = mb_substr($res['text'], 0, 63) . '...';
                    }
                    if (isset($matches[3])) {
                        $res['text'] = $res['text'] . ' | ' . $lng['page'] . ' ' . $matches[3];
                    }
                    return '[url=' . $m[3] . ']' . $res['text'] . '[/url]';
                } else {
                    return $m[3];
                }
            } else {
                return $m[3];
            }
        } else {
            return '[url=' . $m[1] . ']' . $m[2] . '[/url]';
        }
    }

    public static function imgurSize($link, $size = 't') {
        return preg_replace('/\.([a-z]+)$/i', $size . '.$1', $link);
    }

    public static function get_recent_images(){
        $return = array();
        $req = mysql_query('SELECT `id`, `link` FROM `cms_images` WHERE `user_id` = "' . self::$user_id . '" ORDER BY `time` DESC LIMIT 4');
        while($res = mysql_fetch_assoc($req)) {
            if ($res === false) {
                break;
            }
            $return[] = array(
                'img_id'    => $res['id'],
                'img_link'  => $res['link'],
                'img_thumb' => preg_replace('/^http:/i', '', self::imgurSize($res['link'], 's'))
            );
        }
        return $return;
    }

    public static function redirect($url, $error_code) {
        $messages = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',

            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',

            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if (isset($messages[$error_code])) {
            header('HTTP/1.1 ' . $error_code . ' ' . $messages[$error_code]);
        }
        header('Location: ' . $url); exit();
    }

    public static function fixStart($start, $total, $kmess) {
        if ($start >= $total) {
            // Fixing a request for a non-existent page
            $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
        }
        return $start;
    }

    public static function position($text, $chr, $default = 100)
    {
        $result = mb_strpos($text, $chr);

        return $result !== false ? $result : $default;
    }

    public static function closeSite() {
        global $lng;
        $templates = new League\Plates\Engine(TEMPLATE_DIR);
        $content = $templates->render('closed', ['lang' => $lng]);
        die($content);
    }
}
