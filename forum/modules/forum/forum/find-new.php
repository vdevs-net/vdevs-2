<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['forum'] . ' | ' . $lng['unread'];
$headmod = 'forumnew';
require(ROOTPATH . 'system/header.php');
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

$breadcrumb = new breadcrumb(0, 1);
$breadcrumb->add('/forum/', $lng['forum']);

if ($type == 'unread' && $user_id) {
    $breadcrumb->add($lng['unread']);
    // Displays unread topics (for registered)
    $total = counters::forum_new();
    $sql = 'SELECT `phonho_threads`.* FROM `phonho_threads`
        LEFT JOIN `cms_forum_rdm` ON `phonho_threads`.`id` = `cms_forum_rdm`.`topic_id` AND `cms_forum_rdm`.`user_id` = "' . $user_id . '"
        WHERE' . ($rights >= 7 ? '' : ' `phonho_threads`.`thread_deleted` = "0" AND') . ' (`cms_forum_rdm`.`topic_id` Is Null
        OR `phonho_threads`.`time` > `cms_forum_rdm`.`time`)
        ORDER BY `phonho_threads`.`time` DESC
        LIMIT ';
    $this_url = '/forum/find-new?type=unread';
    $tpl_data['hidden_input'] = '<input type="hidden" name="type" value="unread" />';
} elseif ($type == 'recent') {
    $breadcrumb->add('Recent Threads');
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"')), 0);
    $sql = 'SELECT * FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"') . ' ORDER BY `time` DESC LIMIT';
    $this_url = '/forum/find-new?type=recent';
    $tpl_data['hidden_input'] = '<input type="hidden" name="type" value="recent" />';
} else {
    $breadcrumb->add('New Threads');
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"')), 0);
    $sql = 'SELECT * FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"') . ' ORDER BY `id` DESC LIMIT';
    $this_url = '/forum/find-new';
    $tpl_data['hidden_input'] = '';
    $type = '';
}
$_breadcrumb = $breadcrumb->out();

$start = functions::fixStart($start, $total, $kmess);
$max_page = ceil($total / $kmess);
if ($page > $max_page) {
    $page = $max_page;
}
$tpl_data['total'] = $total;
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination(SITE_URL . $this_url . ($type ? '&' : '?') . 'page=', $start, $total, $kmess) : '');
$tpl_data['pagination_form_action'] = 'find-new';
$tpl_data['current_page'] = $page;
$tpl_data['threads'] = [];
if ($total) {
    $req = mysql_query($sql . ' ' . $start . ', ' . $kmess);
    while ($res = mysql_fetch_assoc($req)) {
        if ($type == 'unread' && $user_id) {
            $np = false;
        } else {
            if ($user_id) {
                $np = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_rdm` WHERE `time` >= "' . $res['time'] . '" AND `topic_id` = "' . $res['id'] . '" AND `user_id`="' . $user_id . '"'), 0);
            } else {
                $np = (SYSTEM_TIME - $res['time'] >= 86400);
            }
        }
        $razd = mysql_fetch_assoc(mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `type` = "r" AND `id` = "' . $res['refid'] . '" LIMIT 1'));
        $count = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0);
        if ($count > 1) {
            $nick = mysql_fetch_assoc(mysql_query('SELECT `time`, `user_id`, `from` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' ORDER BY `time` DESC LIMIT 1'));
        } else {
            $nick = ['time' => $res['time'], 'user_id' => $res['user_id'], 'from' => $res['from']];
        }
        $cpg = ceil($count / $kmess);
        // icons
        $icons = array(
            ($np ? (!$res['sticked'] ? 'op' : '') : 'np'),
            ($res['sticked'] ? 'pt' : ''),
            ($res['realid'] ? 'rate' : ''),
            ($res['thread_closed'] ? 'tz' : '')
        );
        $thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/';
        $tpl_data['threads'][] = [
            'html_class'        => ($res['thread_deleted'] ? 'rmenu' : 'menu'),
            'icons'             => array_diff($icons, array('')),
            'prefix'            => $res['prefix'],
            'prefix_name'       => $prefixs[$res['prefix']],
            'name'              => functions::checkout($res['text']),
            'url'               => $thread_url . ($type == 'unread' ? 'unread' : ''),
            'post_count'        => $count,
            'create_time'       => functions::display_date($nick['time']),
            'last_time'         => functions::display_date($res['time']),
            'parent_name'       => functions::checkout($razd['forum_name']),
            'parent_url'        => SITE_URL . '/forum/forums/' . functions::makeUrl($razd['forum_name']) . '.' . $res['refid'] . '/',
            'author_name'       => $res['from'],
            'author_profile'    => SITE_URL . '/profile/' . $res['from'] . '.' . $res['user_id'] .'/',
            'last_page_url'     => ($type != 'unread' && $cpg > 1 ? $thread_url . 'page-' . $cpg : ''),
            'last_user_name'    => $nick['from'],
            'last_user_profile' => SITE_URL . '/profile/' . $nick['from'] . '.' . $nick['user_id'] .'/'
        ];
    }
}
$tpl_data['show_unread_mark_link'] = ($type == 'unread' && $total);
$tpl_data['unread_mark_url'] = 'mark-read';

$tpl_file = 'forum::forum.find-new';
