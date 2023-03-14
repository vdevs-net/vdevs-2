<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $page_title = $lng['who_in_topic'];
    if ($user_id) {
        // show a general list of those who are chosen topic
        $req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);
            $headmod = 'forum-theme-' . $id;
            require(ROOTPATH . 'system/header.php');
            $type = isset($_GET['type']) ? trim($_GET['type']) : '';
            $guest = ($type == 'guest' && $rights > 0);
            $thread_url = '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;

            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url, $res['text']);
            $breadcrumb->add($lng['who_in_topic']);
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
            $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `' . ($guest ? 'cms_sessions' : 'users') . '` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum-theme-' . $id . '%"'), 0);
            $start = functions::fixStart($start, $total, $kmess);
            $tpl_data['total'] = $total;
            $tpl_data['items'] = array();
            $tpl_data['pagination'] = '';
            if ($total > $kmess) {
                $tpl_data['pagination'] = functions::display_pagination($thread_abs_url . 'online?' . ($guest ? 'type=guest&' : '') . 'page=', $start, $total, $kmess);
            }
            if ($total) {
                $req = mysql_query('SELECT * FROM `' . ($guest ? 'cms_sessions' : 'users') . '` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum-theme-' . $id . '%" ORDER BY ' . ($guest ? '`movings` DESC' : '`account` ASC') . ' LIMIT ' . $start . ', ' . $kmess . '');
                while($res = mysql_fetch_assoc($req)) {
                    if($guest) {
                        $res['id'] = 0;
                    }
                    $tpl_data['items'][] = array(
                        'html_class' => ($res['id'] == $user_id ? 'gmenu' : 'menu'),
                        'content'    => functions::display_user($res, [])
                    );
                }
            }
            $tpl_file = 'forum::online';
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_topic_deleted'];
            $tpl_data['back_url'] = SITE_URL . '/forum/';
            $tpl_data['back_text'] = $lng['to_forum'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}