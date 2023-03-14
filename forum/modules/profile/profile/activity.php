<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'profile-' . $user['id'];
// History of activity
$page_title = $user['account'] . ': ' . $lng['activity'];
require(ROOTPATH . 'system/header.php');
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$tpl_data['tabs'] = [
    [
        'url' => $profile_url . 'activity',
        'name' => $lng['messages'],
        'active' => ($type != 'thread')
    ],
    [
        'url' => $profile_url . 'activity?type=thread',
        'name' => $lng['themes'],
        'active' => ($type == 'thread')
    ]
];

switch ($type) {
    case 'thread':
        $tpl_file = 'profile::activity.thread';
        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `user_id` = "' . $user['id'] . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0);
        $tpl_data['total'] = $total;
        $tpl_data['items'] = [];
        if ($total) {
            $req = mysql_query('SELECT `id`, `refid`, `time`, `text` FROM `phonho_threads` WHERE `user_id` = "' . $user['id'] . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted`="0"') . ' ORDER BY `id` DESC LIMIT ' . $start . ', ' . $kmess . '');
            $i = 0;
            while ($res = mysql_fetch_assoc($req)) {
                $post = mysql_fetch_assoc(mysql_query('SELECT `text` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' ORDER BY `id` ASC LIMIT 1'));
                $section = mysql_fetch_assoc(mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $res['refid'] . '"'));
                $text = functions::checkout($post['text'], 2, 2);
                $text = mb_substr($text, 0, 127) . (mb_strlen($text) > 127 ? '...' : '');
                $tpl_data['items'][] = [
                    'thread_url' => SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/',
                    'thread_name' => functions::checkout($res['text']),
                    'message' => $text,
                    'time' => functions::display_date($res['time']),
                    'parent_url' => SITE_URL . '/forum/forums/' . functions::makeUrl($section['forum_name']) . '.' . $res['refid'] . '/',
                    'parent_name' => functions::checkout($section['forum_name'])
                ];
            }
        }
        break;

    default:
        $tpl_file = 'profile::activity.post';
        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `user_id` = "' . $user['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0);
        $tpl_data['total'] = $total;
        $tpl_data['items'] = [];
        if ($total) {
            $req = mysql_query('SELECT `id`, `refid`, `text`, `time` FROM `phonho_posts` WHERE `user_id` = "' . $user['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' ORDER BY `id` DESC LIMIT ' . $start . ', ' . $kmess . '');
            while ($res = mysql_fetch_assoc($req)) {
                $topic = mysql_fetch_assoc(mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '" LIMIT 1'));
                $text = functions::checkout($res['text'], 2, 2);
                $text = mb_substr($text, 0, 127) . (mb_strlen($text) > 127 ? '...' : '');
                $tpl_data['items'][] = [
                    'thread_url' => SITE_URL . '/forum/threads/' . functions::makeUrl($topic['text']) . '.' . $res['refid'] . '/',
                    'thread_name' => functions::checkout($topic['text']),
                    'message' => $text,
                    'post_url' => SITE_URL . '/forum/posts/' . $res['id'] . '/',
                    'time' => functions::display_date($res['time'])
                ];
            }
        }
}
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('activity?' . ($type ? 'type=' . $type . '&' : '') . 'page=', $start, $total, $kmess) : '');