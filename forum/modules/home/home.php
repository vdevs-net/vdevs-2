<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'mainpage';
require(ROOTPATH . 'system/header.php');

$tpl_file = 'home::home';

$set_news = unserialize($set['news']);
$tpl_data['news'] = array(
    'url'   => SITE_PATH . '/news/',
    'items' => array()
);
if ($set_news['view'] > 0) {
    $reqtime = $set_news['days'] ? SYSTEM_TIME - ($set_news['days'] * 86400) : 0;
    $req = mysql_query('SELECT `news`.*, `phonho_threads`.`text` as `tname` FROM `news` LEFT JOIN `phonho_threads` ON `phonho_threads`.`id` = `news`.`kom` WHERE `news`.`time` > "' . $reqtime . '" ORDER BY `news`.`time` DESC LIMIT ' . $set_news['quantity']);
    if (mysql_num_rows($req) > 0) {
        while ($res = mysql_fetch_array($req)) {
            $tpl_data['news']['items'][$res['id']] = array(
                'title'          => '',
                'content'       => '',
                'comment_url'   => '',
                'comment_count' => 0
            );
            if ($set_news['view'] == 1 || $set_news['view'] == 2) {
                $tpl_data['news']['items'][$res['id']]['title'] = functions::checkout($res['name']);
            }
            if ($set_news['view'] == 1 || $set_news['view'] == 3) {
                $text = $res['text'];
                if (mb_strlen($res['text']) > $set_news['size']) {
                    $text = mb_substr($text, 0, $set_news['size']);
                }
                $text = functions::checkout(
                    $text,
                    ($set_news['breaks'] ? 1 : 0),
                    ($set_news['tags'] ? 1 : 2),
                    ($set_news['smileys'] ? 2 : 0)
                );
                if (mb_strlen($res['text']) > $set_news['size']) {
                    $text .= ' <a href="news/">' . $lng['read_more'] . '...</a>';
                }
                $tpl_data['news']['items'][$res['id']]['content'] = $text;
            }
            if ($res['kom'] && $set_news['view'] != 2 && $set_news['kom'] == 1) {
                $komm = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['kom'] . '"'), 0) - 1;
                if ($komm >= 0) {
                    $tpl_data['news']['items'][$res['id']]['comment_url'] = SITE_PATH . '/forum/threads/' . functions::makeUrl($res['tname']) . '.' . $res['kom'] . '/';
                    $tpl_data['news']['items'][$res['id']]['comment_count'] = $komm;
                }
            }
        }
    }
}

$tpl_data['forum_open'] = false;
// Forum
if ($set['mod_forum'] || $rights >= 7) {
    $tpl_data['forum_open'] = true;
    $tpl_data['forum_url']  = SITE_PATH . '/forum/';
    $tpl_data['forum_unread'] = counters::forum_new();
    $tpl_data['forum_unread_url'] = SITE_PATH . '/forum/find-new?type=unread';
    $tpl_data['forum_new_url'] = SITE_PATH . '/forum/find-new';
    $tpl_data['forum_recent_url'] = SITE_PATH . '/forum/find-new?type=recent';
    // Recent topics
    $tpl_data['recent_threads']  = array();
    if (mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"')), 0)) {
        $req = mysql_query('SELECT * FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"') . ' ORDER BY `time` DESC LIMIT ' . $kmess);
        while($res = mysql_fetch_assoc($req)) {
            if ($user_id) {
                $np = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_rdm` WHERE `time` >= "' . $res['time'] . '" AND `topic_id` = "' . $res['id'] . '" AND `user_id`="' . $user_id . '"'), 0);
            } else {
                $np = (SYSTEM_TIME - $res['time'] >= 86400);
            }
            $count = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0);
            if ($count > 1) {
                $nam = mysql_fetch_assoc(mysql_query('SELECT `user_id`, `from` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' ORDER BY `time` DESC LIMIT 1'));
            } else {
                $nam = array('user_id' => $res['user_id'], 'from' => $res['from']);
            }
            // icons
            $icons = array(
                ($np ? (!$res['sticked'] ? 'op' : '') : 'np'),
                ($res['sticked'] ? 'pt' : ''),
                ($res['realid'] ? 'rate' : ''),
                ($res['thread_closed'] ? 'tz' : '')
            );
            $thread_url  = SITE_PATH . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/' . ($user_id ? 'unread' : '');
            $tpl_data['recent_threads'][] = array(
                'class'           => ($res['thread_deleted'] ? 'rmenu list-group-item-dange' : 'menu'),
                'name'            => functions::checkout($res['text']),
                'url'             => $thread_url,
                'icons'           => array_diff($icons, array('')),
                'post_count'      => $count,
                'last_user_url'   => SITE_PATH . '/profile/' . $nam['from'] . '.' . $nam['user_id'] . '/',
                'last_user_name'  => $nam['from'],
                'prefix'          => $res['prefix'],
                'prefix_name'     => $prefixs[$res['prefix']]
            );
        }
    }
    // Portal
    $tpl_data['portal']  = array(
        'items' => array(),
        'next_url' => '',
        'prev_url' => ''
    );
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `portal` != "0"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0);
    if ($total) {
        $thisKmess = 5;
        $start = ($page - 1) * $thisKmess;
        $start = functions::fixStart($start, $total, $thisKmess);
        $max_page = ceil($total / $thisKmess);
        if ($page > $max_page) {
            $page = $max_page;
        }
        $html_links[] = ['rel' => 'canonical', 'href' => SITE_PATH . ($page > 1 ? '/?page=' . $page : '')];
        $req = mysql_query('SELECT `id`, `text`, `user_id`, `from`, `time` FROM `phonho_threads` WHERE `portal` != "0"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"') . ' ORDER BY `id` DESC LIMIT ' . $start . ', ' . $thisKmess);
        $size = ($device == 'wap' ? 's' : 'b');
        while ($res = mysql_fetch_assoc($req)) {
            $post_res = mysql_fetch_assoc(mysql_query('SELECT `time`, `text` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '" ORDER BY `id` ASC LIMIT 1'));
            if (preg_match('#\[img](.+?)\[/img]#i', $post_res['text'], $matches)) {
                $img = $matches[1];
                $img = preg_replace('#https?://i\.imgur\.com/([\da-z]+)\.(png|jpg)#i', '//i.imgur.com/$1' . $size . '.$2', $img);
            } else {
                $img = SITE_PATH . '/assets/images/no-thumb.png';
            }
            $arr = preg_split('/[\r\n]|\r\n/', $post_res['text']);
            $text = array_shift($arr);
            $thread_url  = SITE_PATH . '/forum/threads/' .functions::makeUrl($res['text']) . '.' . $res['id'] . '/';
            $tpl_data['portal']['items'][] = array(
                'name'        => functions::checkout($res['text']),
                'url'         => $thread_url,
                'author_url'  => SITE_PATH . '/profile/' . $res['from'] . '.' . $res['user_id'] . '/',
                'author_name' => $res['from'],
                'time'        => functions::display_date($post_res['time']),
                'thumb'       => functions::checkout($img),
                'content'     => functions::checkout($text, 2, 2, 0)
            );
        }
        if ($total > $thisKmess) {
            if ($page > 1) {
                $prev_url = SITE_PATH . ($page > 2 ? '/?page=' . ($page - 1) : '');
                $html_links[] = ['rel' => 'prev', 'href' => $prev_url];
                $tpl_data['portal']['prev_url'] = $prev_url;
            }
            if ($page < $max_page) {
                $next_url = SITE_PATH . '/?page=' . ($page + 1);
                $html_links[] = ['rel' => 'next', 'href' => $next_url];
                $tpl_data['portal']['next_url'] = $next_url;
            }
        }
    }
    
    // sticked threads
    $tpl_data['sticked_threads'] = array();
    if (mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `sticked` = "1"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0)) {
        $req = mysql_query('SELECT `id`, `text`, `prefix`, `user_id`, `from` FROM `phonho_threads` WHERE `sticked` = "1"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"') . ' ORDER BY `time` DESC LIMIT 5');
        while($res = mysql_fetch_assoc($req)) {
            $count = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0);
            $cpg = ceil($count / $kmess);
            if ($count > 1) {
                $nam = mysql_fetch_assoc(mysql_query('SELECT `user_id`, `from` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' ORDER BY `time` DESC LIMIT 1'));
            } else {
                $nam = array('user_id' => $res['user_id'], 'from' => $res['from']);
            }
            // icons
            $icons = array(
                'pt'
            );
            $thread_url  = SITE_PATH . '/forum/threads/' .functions::makeUrl($res['text']) . '.' . $res['id'] . '/';
            $tpl_data['sticked_threads'][] = array(
                'name'           => functions::checkout($res['text']),
                'url'            => $thread_url,
                'icons'          => array_diff($icons, array('')),
                'post_count'     => $count,
                'last_page_url'  => ($cpg > 1 ? ($thread_url . 'page-' . $cpg) : ''),
                'last_user_name' => $nam['from'],
                'last_user_url'  => SITE_PATH . '/profile/' . $nam['from'] . '.' . $nam['user_id'] . '/',
                'prefix'         => $res['prefix'],
                'prefix_name'    => $prefixs[$res['prefix']]
            );
        }
    }
}
// Thống kê trang web
$tpl_data['users_online_url'] = SITE_PATH . '/users/online';
$statsCache = ROOTPATH . 'files/system/cache/stats.dat';
if (file_exists($statsCache) && filemtime($statsCache) >= SYSTEM_TIME - 10) {
    $tpl_data['stats'] = unserialize(file_get_contents($statsCache));
} else {
    $robots_online = $gbot = $yahoo = $msn = $baidu = $bing = $mj = $coccoc = $facebook = $yandex = $ahref = $semrush = 0;
    $users_online_list = $robots_online_list = array();
    $users_online = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `lastdate` > "' . (SYSTEM_TIME - 300) . '" AND `preg`="1"'), 0);
    $guests_online = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > "' . (SYSTEM_TIME - 300) . '"'), 0);
    // newest register user
    $lastUser = mysql_fetch_assoc(mysql_query('SELECT `id`, `account` FROM `users` ORDER BY `datereg` DESC LIMIT 1'));

    if ($users_online) {
        $req = mysql_query('SELECT `id`, `account`, `rights` FROM `users` WHERE `preg`="1" and `lastdate` > "'. (SYSTEM_TIME - 300) .'" ORDER BY `account` ASC');
        while ($res = mysql_fetch_assoc($req)) {
            $users_online_list[] = '<a href="' . SITE_PATH . '/profile/' . $res['account'] . '.' . $res['id'] . '/" class="user_' . $res['rights'] . '">' . $res['account'] . '</a>';
        }
    }

    if ($guests_online) {
        $spider = mysql_query('SELECT `browser` FROM `cms_sessions` WHERE `lastdate` > "' . (SYSTEM_TIME - 300) . '"');
        while ($res = mysql_fetch_assoc($spider)) {
            $res['browser'] = mb_strtolower($res['browser']);
            if(stristr($res['browser'], 'google')) ++$gbot;
            if(stristr($res['browser'], 'yahoo.com')) ++$yahoo;
            if(stristr($res['browser'], 'msnbot')) ++$msn;
            if(stristr($res['browser'], 'baidu')) ++$baidu;
            if(stristr($res['browser'], 'bingbot')) ++$bing;
            if(stristr($res['browser'], 'mj12')) ++$mj;
            if(stristr($res['browser'], 'coccoc')) ++$coccoc;
            if(stristr($res['browser'], 'facebook')) ++$facebook;
            if(stristr($res['browser'], 'yandex')) ++$yandex;
            if(stristr($res['browser'], 'ahrefsbot')) ++$ahref;
            if(stristr($res['browser'], 'SemrushBot')) ++$semrush;
        }
        $robots_online = $gbot + $yahoo + $msn + $baidu + $bing + $mj + $coccoc + $facebook + $yandex + $ahref + $semrush;
        if ($robots_online) {
            if ($gbot) $robots_online_list[] = '(' . $gbot . ') <span style="font-family:Georgia,Verdana;"><span style="color:#1849b5">G</span><span style="color:#de3018">o</span><span style="color:#efbA00">o</span><span style="color:#1849b5">g</span><span style="color:#31b639">l</span><span style="color:#de3018">e</span></span>';
            if ($yahoo) $robots_online_list[] = '(' . $yahoo . ') Yahoo';
            if ($msn) $robots_online_list[] = '(' . $msn . ') MSN';
            if ($baidu) $robots_online_list[] = '(' . $baidu . ') Baidu';
            if ($bing) $robots_online_list[] = '(' . $bing . ') Bing';
            if ($mj) $robots_online_list[] = '(' . $mj . ') MJ12';
            if ($coccoc) $robots_online_list[] = '(' . $coccoc . ') CốcCốc';
            if ($facebook) $robots_online_list[] = '(' . $facebook . ') Facebook';
            if ($yandex) $robots_online_list[] = '(' . $yandex . ') Yandex';
            if ($ahref) $robots_online_list[] = '(' . $ahref . ') Ahrefs';
            if ($semrush) $robots_online_list[] = '(' . $semrush . ') SemrushBot';
        }
    }
    $last_search = array();
    $req = mysql_query('SELECT `url`, `query` FROM `stat_robots` ORDER BY `date` DESC LIMIT 10');
    while ($res = mysql_fetch_assoc($req)) {
        if (preg_match('~^https?://~', $res['query'])) continue;
        $last_search[] = '<a href="' . htmlspecialchars($res['url']) . '" rel="nofollow">' . htmlspecialchars($res['query']) . '</a>';
    }
    $tpl_data['stats'] = array(
        'forum'             => counters::forum(),
        'last_search'       => $last_search,
        'count_users'       => counters::users(),
        'total_online'      => ($users_online + $guests_online),
        'users_online'      => $users_online,
        'guests_online'     => ($guests_online - $robots_online),
        'robots_online'     => $robots_online,
        'online_list'       => implode(', ', array_merge($users_online_list, $robots_online_list)),
        'last_user_name'    => $lastUser['account'],
        'last_user_url'     => SITE_PATH . '/profile/' . $lastUser['account'] . '.' . $lastUser['id'] . '/'
    );
    file_put_contents($statsCache, serialize($tpl_data['stats']), LOCK_EX);
}