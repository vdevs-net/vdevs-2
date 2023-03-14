<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $type = mysql_query('SELECT `refid`, `type`, `forum_name` FROM `phonho_forums` WHERE `id`= "' . $id . '" AND `type` ="r" LIMIT 1');
    if (mysql_num_rows($type)) {
        $type1 = mysql_fetch_assoc($type);
        $page_title = $type1['forum_name'];
        $headmod = 'forum-section-' . $id;
        require(ROOTPATH . 'system/header.php');
        $forum_url = '/forum/forums/' . functions::makeUrl($type1['forum_name']) . '.' . $id . '/';
        $forum_abs_url = SITE_URL . $forum_url;

        get_breadcrumb($type1['refid'], [$type1['forum_name']], $_breadcrumb);

        $tpl_data['search_url'] = SITE_URL . '/forum/search';
        $tpl_data['forum_unread_count'] = counters::forum_new();
        $tpl_data['forum_unread_url'] = SITE_URL . '/forum/find-new?type=unread';

        ////////////////////////////////////////////////////////////
        // List of topics                                         //
        ////////////////////////////////////////////////////////////
        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `refid`="' . $id . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0);
        $start = functions::fixStart($start, $total, $kmess);
        $max_page = ceil($total / $kmess);
        if ($page > $max_page) {
            $page = $max_page;
        }
        $tpl_data['can_create_thread'] = false;
        $tpl_data['create_thread_url'] = $forum_abs_url . 'new-thread';
        if (($user_id && !isset($ban['1']) && !isset($ban['11']) && $set['mod_forum'] != 4) || $rights) {
            $tpl_data['can_create_thread'] = true;
        }
        $tpl_data['total'] = $total;
        $tpl_data['pagination'] = '';
        $tpl_data['threads'] = array();
        if ($total) {
            $req = mysql_query('SELECT * FROM `phonho_threads` WHERE `refid` = "' . $id . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"') . ' ORDER BY `sticked` DESC, `time` DESC LIMIT ' . $start . ', ' . $kmess . '');
            while ($res = mysql_fetch_assoc($req)) {
                $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `phonho_posts` WHERE `refid`='" . $res['id'] . "'" . ($rights >= 7 ? '' : " AND `post_deleted` != '1'")), 0);
                if ($count > 1) {
                    $nam = mysql_fetch_assoc(mysql_query('SELECT `user_id`, `from`, `time` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `post_deleted`!="1"') . ' ORDER BY `time` DESC LIMIT 1'));
                } else {
                    $nam = array('user_id' => $res['user_id'], 'from' => $res['from'], 'time' => $res['time']);
                }
                $cpg = ceil($count / $kmess);
                if ($user_id) {
                    $np = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_rdm` WHERE `time` >= "' . $res['time'] . '" AND `topic_id` = "' . $res['id'] . '" AND `user_id`="' . $user_id . '"'), 0);
                } else {
                    $np = (SYSTEM_TIME - $res['time'] >= 86400);
                }
                // badges
                $icons = array(
                    ($np ? (!$res['sticked'] ? 'op' : '') : 'np'),
                    ($res['sticked'] ? 'pt' : ''),
                    ($res['realid'] ? 'rate' : ''),
                    ($res['thread_closed'] ? 'tz' : '')
                );
                $thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/';
                $tpl_data['threads'][] = array(
                    'html_class'     => ($res['thread_deleted'] ? 'rmenu' : 'menu'),
                    'icons'          => array_diff($icons, array('')),
                    'prefix'         => $res['prefix'],
                    'prefix_name'    => $prefixs[$res['prefix']],
                    'name'           => functions::checkout($res['text']),
                    'url'            => $thread_url,
                    'create_time'    => functions::display_date($nam['time']),
                    'last_time'      => functions::display_date($res['time']),
                    'author_name'    => $res['from'],
                    'author_profile' => SITE_URL . '/profile/' . $res['from'] . '.' . $res['user_id'] . '/',
                    'post_count'     => $count,
                    'last_page_url'  => ($cpg > 1 ? $thread_url . 'page-' . $cpg : ''),
                    'last_user_name' => $nam['from'],
                    'last_profile'   => SITE_URL . '/profile/' . $nam['from'] . '.' . $nam['user_id'] . '/'
                );
            }
            if ($total > $kmess) {
                $tpl_data['pagination'] = functions::display_pagination($forum_url . 'page-', $start, $total, $kmess);
            }
        }
        $tpl_file = 'forum::forums';
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}