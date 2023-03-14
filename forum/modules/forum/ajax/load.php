<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if (IS_AJAX) {
    header('Content-Type: application/json; Charset=UTF-8');
    $mode  = isset($_GET['mode']) ? trim($_GET['mode']) : '';
    switch ($mode)
    {
        case 'lastest':
            $sql = 'SELECT * FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"') . ' ORDER BY `id` DESC LIMIT 10';
            break;
        case 'unread':
            if ($user_id) {
                $sql = 'SELECT `phonho_threads`.* FROM `phonho_threads`
                    LEFT JOIN `cms_forum_rdm` ON `phonho_threads`.`id` = `cms_forum_rdm`.`topic_id` AND `cms_forum_rdm`.`user_id` = "' . $user_id . '"
                    WHERE' . ($rights >= 7 ? '' : ' `phonho_threads`.`thread_deleted` = "0" AND') . ' (`cms_forum_rdm`.`topic_id` Is Null
                    OR `phonho_threads`.`time` > `cms_forum_rdm`.`time`)
                    ORDER BY `phonho_threads`.`time` DESC
                    LIMIT 10';
                break;
            }
        default:
            $sql = 'SELECT * FROM `phonho_threads`' . ($rights >= 7 ? '' : ' WHERE `thread_deleted` = "0"') . ' ORDER BY `time` DESC LIMIT 10';
    }
    $req = mysql_query($sql);
    $ajax_data['messages'] = [];
    $ajax_data['threads'] = [];
    while ($res = mysql_fetch_assoc($req)) {
        if ($mode == 'unread' && $user_id) {
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
        // icons
        $icons = array(
            ($np ? (!$res['sticked'] ? 'op' : '') : 'np'),
            ($res['sticked'] ? 'pt' : ''),
            ($res['realid'] ? 'rate' : ''),
            ($res['thread_closed'] ? 'tz' : '')
        );
        $thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/' . ($user_id ? 'unread' : '');
        $ajax_data['threads'][] = [
            'class'             => ($res['thread_deleted'] ? 'rmenu' : 'menu'),
            'icons'             => array_diff($icons, array('')),
            'prefix'            => (int) $res['prefix'],
            'prefix_name'       => $prefixs[$res['prefix']],
            'name'              => functions::checkout($res['text']),
            'url'               => $thread_url,
            'post_count'        => $count,
            'create_time'       => functions::display_date($nick['time']),
            'last_time'         => functions::display_date($res['time']),
            'parent_name'       => functions::checkout($razd['forum_name']),
            'parent_url'        => SITE_URL . '/forum/forums/' . functions::makeUrl($razd['forum_name']) . '.' . $res['refid'] . '/',
            'author_name'       => $res['from'],
            'author_profile'    => SITE_URL . '/profile/' . $res['from'] . '.' . $res['user_id'] .'/',
            'last_user_name'    => $nick['from'],
            'last_user_url' => SITE_URL . '/profile/' . $nick['from'] . '.' . $nick['user_id'] .'/'
        ];
    }
    die(json_encode($ajax_data));
}
