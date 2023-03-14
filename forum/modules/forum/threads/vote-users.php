<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights >= 7) {
        $topic_r = mysql_query('SELECT `text`, `realid` FROM `phonho_threads` WHERE `id`="' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_closed` != "1"') . ' LIMIT 1');
        if (mysql_num_rows($topic_r)) {
            $topic = mysql_fetch_assoc($topic_r);
            $headmod = 'forum-theme-' . $id;
            $page_title = $lng['voting_users'];
            require(ROOTPATH . 'system/header.php');
            $thread_url = '/forum/threads/' . functions::makeUrl($topic['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;

            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url, $topic['text']);
            $breadcrumb->add($lng['voting_users']);
            $_breadcrumb = $breadcrumb->out();

            if ($topic['realid']) {
                $tpl_file = 'forum::threads.vote-users';
                $topic_vote = mysql_fetch_assoc(mysql_query('SELECT `name` FROM `cms_forum_vote` WHERE `type` = "1" AND `topic` = "' . $id . '" LIMIT 1'));
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote_users` WHERE `topic`="' . $id . '"'), 0);
                $tpl_data['total'] = $total;
                $tpl_data['items'] = [];
                $tpl_data['pagination'] = ($total > $kmess? functions::display_pagination('vote-users?page=', $start, $total, $kmess) : '');
                if ($total) {
                    $req = mysql_query('SELECT `cms_forum_vote_users`.*, `users`.`rights`, `users`.`lastdate`, `users`.`account`, `users`.`sex`, `users`.`status`, `users`.`datereg`, `users`.`id`
                    FROM `cms_forum_vote_users` LEFT JOIN `users` ON `cms_forum_vote_users`.`user` = `users`.`id`
                    WHERE `cms_forum_vote_users`.`topic`="' . $id . '" LIMIT ' . $start . ', ' . $kmess);
                    while ($res = mysql_fetch_array($req)) {
                        $tpl_data['items'][] = [
                            'content' => functions::display_user($res, ['iphide' => 1])
                        ];
                    }
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
                $tpl_data['back_url'] = $thread_abs_url;
                $tpl_data['back_text'] = $lng['back'];
            }
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}