<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($user_id) {
        $req = mysql_query('SELECT `user_id`, `refid` FROM `phonho_posts` WHERE `id`="' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);	
            $text = mysql_result(mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id`="' . $res['refid'] . '" LIMIT 1'), 0);
            $thread_url = '/forum/threads/' . functions::makeUrl($text) . '.' . $res['refid'] . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            $position = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <= "'. $id .'"' . ($rights < 7 ? ' AND `post_deleted` != "1"' : '')), 0);
            $cpg = ceil($position / $kmess);
            $page_title = 'Người dùng thích bài viết';
            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url . 'page-' . $cpg . '#' . $id, $text);
            $breadcrumb->add('Người dùng thích bài viết #' . $position);
            $_breadcrumb = $breadcrumb->out();
            require(ROOTPATH . 'system/header.php');
            $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_likes` WHERE `type`="1" AND `sub_id` = "'. $id .'"'), 0);
            $tpl_data['total'] = $total;
            $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('likes?page=', $start, $total, $kmess) : '');
            $tpl_data['items'] = [];
            if ($total) {
                $req2 = mysql_query('SELECT `cms_likes`.`user_like` as `user_id`,`users`.`account`, `users`.`rights` FROM `cms_likes` LEFT JOIN `users` ON `users`.`id` = `cms_likes`.`user_like` WHERE `cms_likes`.`type`="1" AND `cms_likes`.`sub_id`="'.$id.'" ORDER BY `cms_likes`.`id` DESC LIMIT ' . $start . ', ' . $kmess);
                while ($res2 = mysql_fetch_assoc($req2)) {
                    $tpl_data['items'][] = [
                        'id' => $res2['user_id'],
                        'html_class' => 'user_' . $res2['rights'],
                        'profile_url' => SITE_URL . '/profile/' . $res2['account'] . '.' . $res2['user_id'] . '/',
                        'name' => $res2['account']
                    ];
                }
            }
            $tpl_file = 'forum::posts.likes';
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_post_deleted'];
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}