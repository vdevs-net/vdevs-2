<?php
defined('_MRKEN_CMS') or die('Error: restricted access');
define('HEADER_LOADED', 1);

$headmod = isset($headmod) ? $headmod : '';
$page_title = (empty($page_title) ? '' : $page_title . ' | ') . ($page > 1 ? $lng['page'] . ' ' . $page . ' | ' : '') . $set['copyright'];
$meta_key = (empty($meta_key) ? '' : $meta_key . ', ') . $set['meta_key'];
$meta_desc = empty($meta_desc) ? $set['meta_desc'] : $meta_desc;
$statistic = new statistic($page_title);

// meta tags
$meta_tags[] = ['name' => 'http-equiv', 'value' => 'Content-Type', 'content' => 'text/html; charset=utf-8'];
$meta_tags[] = ['name' => 'http-equiv', 'value' => 'X-UA-Compatible', 'content' => 'IE=edge'];
$meta_tags[] = ['name' => 'name', 'value' => 'viewport', 'content' => 'width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no'];
$meta_tags[] = ['name' => 'name', 'value' => 'google-site-verification', 'content' => GSV_CODE];

$meta_tags[] = ['name' => 'name', 'value' => 'keywords', 'content' => functions::checkout($meta_key)];
$meta_tags[] = ['name' => 'name', 'value' => 'description', 'content' => functions::checkout($meta_desc)];
$meta_tags[] = ['name' => 'property', 'value' => 'fb:app_id', 'content' => FB_APP_ID];

$meta_tags = array_merge($meta_tags, $handle_meta_tags);

// Links
$html_links[] = ['rel' => 'shortcut icon', 'href' => SITE_PATH . '/favicon.ico'];
$html_links[] = ['rel' => 'alternate', 'type' => 'application/rss+xml', 'title' => 'RSS | ' . $lng['site_news'], 'href' => SITE_PATH . '/rss/rss.php'];

// javascript
if (!DEV_MODE) {
    $html_js[] = ['ext' => 0, 'content' => '(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,"script","https://www.google-analytics.com/analytics.js","ga");ga("create", "' . GA_ID . '", "auto");ga("send", "pageview");'];
}

// Load ADS
$cms_ads = array('', '', '', '');
if ($headmod != 'admin') {
    $ads_req = mysql_query('SELECT * FROM `cms_ads` WHERE `to` = "0" AND (`layout` = "' . ($headmod == 'mainpage' ? 1 : 2) . '" or `layout` = "0") AND (`view` = "' . ($user_id ? 2 : 1) . '" or `view` = "0") ORDER BY  `mesto` ASC');
    if (mysql_num_rows($ads_req)) {
        while ($ads_res = mysql_fetch_assoc($ads_req)) {
            $name = explode('|', $ads_res['name']);
            $name = functions::checkout($name[mt_rand(0, (count($name) - 1))]);
            if (!empty($ads_res['color'])) {
                $name = '<span style="color:#' . $ads_res['color'] . '">' . $name . '</span>';
            }
            $cms_ads[$ads_res['type']] .= '<div><a href="' . ($ads_res['show'] ? functions::checkout($ads_res['link']) : SITE_PATH . '/misc/go?id=' . $ads_res['id']) . '">' . $name . '</a></div>';
            if (($ads_res['day'] != 0 && SYSTEM_TIME >= ($ads_res['time'] + $ads_res['day'] * 3600 * 24)) || ($ads_res['count_link'] != 0 && $ads_res['count'] >= $ads_res['count_link'])) {
                mysql_query("UPDATE `cms_ads` SET `to` = '1'  WHERE `id` = '" . $ads_res['id'] . "'");
            }
        }
    }
}

// Update visitor locations
$sql = '';
if ($user_id) {
	$movings = $datauser['movings'];
	if ($datauser['lastdate'] < (SYSTEM_TIME - 300)) {
		$movings = 0;
		$sql .= ' `sestime` = "' . SYSTEM_TIME . '", ';
	}
	if ($datauser['place'] != $headmod) {
		++$movings;
		$sql .= ' `place` = "' . $headmod . '", ';
	}
	if ($datauser['browser'] != $agn){
		$sql .= ' `browser` = "' . mysql_real_escape_string($agn) . '", ';
	}
	$totalonsite = $datauser['total_on_site'];
	if ($datauser['lastdate'] > (SYSTEM_TIME - 300)){
		$totalonsite = $totalonsite + SYSTEM_TIME - $datauser['lastdate'];
	}
    if (date('d', $datauser['day_time']) != date('d', SYSTEM_TIME) || date('m', $datauser['day_time']) != date('m', SYSTEM_TIME) || date('Y', $datauser['day_time']) != date('Y', SYSTEM_TIME)) {
        $coin_plus = rand(40,50) + ($rights ? 50 : 0);
        $datauser['coin'] = $datauser['coin'] + $coin_plus;
        $sql .= ' `coin` = "' . $datauser['coin'] . '", `day_time` = "' . SYSTEM_TIME . '", ';
        $notif = '<div class="rmenu">Bạn nhận được ' . $coin_plus . ' xu cho việc đăng nhập trong ngày hôm nay!</div>';
    }
    $datauser['lastdate'] = SYSTEM_TIME;
    mysql_query('UPDATE `users` SET ' . $sql .'
        `movings`       = "' . $movings. '",
        `total_on_site` = "' . $totalonsite . '",
        `lastdate`      = "' .  $datauser['lastdate'] . '"
        WHERE `id`      = "' . $user_id . '"
    ');
} else {
    $movings = 0;
    $session = md5(core::$ip . core::$ip_via_proxy . core::$user_agent);
    $session_req = mysql_query('SELECT * FROM `cms_sessions` WHERE `session_id` = "' . $session . '" LIMIT 1');
    if (mysql_num_rows($session_req)) {
        // If there is in the database, then update the data
        $session_res = mysql_fetch_assoc($session_req);
        $movings = ++$session_res['movings'];
        if ($session_res['sestime'] < (SYSTEM_TIME - 300)) {
            $movings = 1;
            $sql .= ' `sestime` = "' . SYSTEM_TIME . '", ';
        }
        if ($session_res['place'] != $headmod) {
            $sql .= ' `place` = "' . $headmod . '", ';
        }
        mysql_query('UPDATE `cms_sessions` SET ' . $sql . '
            `movings`  = "' . $movings . '",
            `lastdate` = "' . SYSTEM_TIME . '"
            WHERE `session_id` = "' . $session . '"
        ');
    } else {
        // 	If still was not in the database, the record is added
        mysql_query('INSERT INTO `cms_sessions` SET
            `session_id`   = "' . $session . '",
            `ip`           = "' . core::$ip . '",
            `ip_via_proxy` = "' . core::$ip_via_proxy . '",
            `browser`      = "' . mysql_real_escape_string($agn) . '",
            `lastdate`     = "' . SYSTEM_TIME . '",
            `sestime`      = "' . SYSTEM_TIME . '",
            `place`        = "' . $headmod . '"
        ');
    }
}
unset($sql, $movings);

// UPDATE BOT time
mysql_query('UPDATE `users` SET `lastdate` = "' . SYSTEM_TIME . '" WHERE `id`="2"');

header('X-Frame-Options: SAMEORIGIN');

// Links to unread
if ($user_id) {
	// system mail
    $unread_notification = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user_id . '" AND `read`="0" AND `sys`="1" AND `delete` != "' . $user_id . '";'), 0);
	// user mail
	$unread_message = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_mail` WHERE `from_id` = "' . $user_id . '" AND `sys` = "0" AND `read` = "0" AND `delete` != "' . $user_id . '"'), 0);
}
