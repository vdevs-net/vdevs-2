<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['who_in_forum'];
if ($user_id) {
    $headmod = 'forumwho';
    require(ROOTPATH . 'system/header.php');
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $guest = ($type == 'guest' && $rights > 0);

    $breadcrumb = new breadcrumb(0, 1);
    $breadcrumb->add('/forum/', $lng['forum']);
    $breadcrumb->add($lng['who_in_forum']);
    $_breadcrumb = $breadcrumb->out();

    $tpl_data['tabs'] = [];
    if ($rights > 0) {
        $tpl_data['tabs'] = [
            [
                'url' => 'online',
                'name' => $lng['users'],
                'active' => !$guest
            ],
            [
                'url' => 'online?type=guest',
                'name' => $lng['guests'],
                'active' => $guest
            ]
        ];
    }
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `' . ($guest ? 'cms_sessions' : 'users') . '` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum%"'), 0);
    $start = functions::fixStart($start, $total, $kmess);
    $tpl_data['total'] = $total;
    $tpl_data['items'] = array();
    $tpl_data['pagination'] = '';
    if ($total > $kmess) {
        $tpl_data['pagination'] = functions::display_pagination('online?' . ($guest ? 'type=guest&' : '') . 'page=', $start, $total, $kmess);
    }
    if ($total) {
        $req = mysql_query('SELECT * FROM `' . ($guest ? 'cms_sessions' : 'users') . '` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum%" ORDER BY ' . ($guest ? '`movings` DESC' : '`account` ASC') . ' LIMIT ' . $start . ', ' . $kmess . '');
        while ($res = mysql_fetch_assoc($req)) {
            if ($guest) {
                $res['id'] = 0;
            }
            // process location
            $place = '<a href="' . SITE_URL . '/forum/">' . $lng['place_main'] . '</a>';
            switch ($res['place']) {
                case 'forum':
                    $place = '<a href="' . SITE_URL . '/forum/">' . $lng['place_main'] . '</a>';
                    break;

                case 'forumwho':
                    $place = $lng['place_list'];
                    break;

                case 'forumnew':
                    $place = '<a href="find-new">' . $lng['place_new'] . '</a>';
                    break;

                case 'forumsearch':
                    $place = '<a href="search">' . $lng['place_search'] . '</a>';
                    break;

                default:
                    $where = explode('-', $res['place']);
                    if ($where[0] == 'forum' && intval($where[2])) {
                        if ($where[1] == 'category') {
                            $req_t = mysql_query('SELECT `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $where[2] . '" AND `type` = "f" LIMIT 1');
                            if (mysql_num_rows($req_t)) {
                                $res_t = mysql_fetch_assoc($req_t);
                                if ($res_t['refid']) {
                                    $url = SITE_URL . '/forum/categories/' . functions::makeUrl($res_t['forum_name']) . '.' . $where[2] . '/';
                                } else {
                                    $url = SITE_URL . '/forum/#' . functions::makeUrl($res_t['forum_name']) . '-' . $where[2];
                                }
                                $place = $lng['place_category'] . ' &quot;<a href="' . $url . '">' . functions::checkout($res_t['forum_name']) . '</a>&quot;';
                            }
                        } elseif ($where[1] == 'section') {
                            $req_t = mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $where[2] . '" AND `type` = "r" LIMIT 1');
                            if (mysql_num_rows($req_t)) {
                                $res_t = mysql_fetch_assoc($req_t);
                                $place = $lng['place_section'] . ' &quot;<a href="' . SITE_URL . '/forum/forums/' . functions::makeUrl($res_t['forum_name']) . '.' . $where[2] . '/">' . functions::checkout($res_t['forum_name']) . '</a>&quot;';
                            }
                        } else {
                            $req_t = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $where[2] . '" LIMIT 1');
                            if (mysql_num_rows($req_t)) {
                                $res_t = mysql_fetch_assoc($req_t);
                                $var = isset($where[3]) ? intval($where[3]) : 0;
                                $place = ($var ? ($var == 2 ? $lng['place_answer'] : $lng['place_write']) : $lng['place_topic']) . ' &quot;<a href="' . SITE_URL . '/forum/threads/' . functions::makeUrl($res_t['text']) . '.' . $where[2] . '/">' . functions::checkout($res_t['text']) . '</a>&quot;';
                            }
                        }
                    }
            }
            $arg = [
                'stshide' => 1,
                'header'  => ($place)
            ];
            $tpl_data['items'][] = array(
                'html_class' => ($res['id'] == $user_id ? 'gmenu' : 'menu'),
                'content'    => functions::display_user($res, $arg)
            );
        }
    }
    $tpl_file = 'forum::online';
} else {
    $error_rights = true;
}