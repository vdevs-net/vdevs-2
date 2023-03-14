<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $type = mysql_query('SELECT * FROM `phonho_threads` WHERE `id`= "' . $id . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"') . ' LIMIT 1');
    if (mysql_num_rows($type)) {
        $tpl_file = 'forum::threads';
        $type1 = mysql_fetch_assoc($type);
        $headmod = 'forum-theme-' . $id;
        $page_title = $type1['text'];
        $thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($type1['text']) . '.' . $id . '/';
        if (!empty($type1['soft'])) {
            $meta_key = functions::show_tags($type1['soft']);
        }
        $txt = mysql_result(mysql_query('SELECT `text` FROM `phonho_posts` WHERE `refid`="' . $id . '" ORDER BY `id` ASC LIMIT 1'), 0);
        // thread img
        if (preg_match('#\[img](.+?)\[/img]#i', $txt, $matches)) {
            $img = $matches[1];
        } else {
            $img = SITE_URL . '/assets/images/nocover.jpg';
        }
        // thread description
        $txt = preg_replace('#\[php](.+?)\[/php]#is', "\r\n", $txt);
        $txt = preg_replace('#\[code](.+?)\[/code]#is', "\r\n", $txt);
        $txt = preg_replace('#\[code=([a-z]+)](.+?)\[/code]#is', "\r\n", $txt);
        $matches = preg_split('#(\r\n|[\r\n]|\.\s)#', $txt);
        $matches = array_map('trim', $matches);
        $matches = array_filter($matches);
        $descriptions = array();
        foreach ($matches as $match) {
            if (mb_strlen(implode('. ', $descriptions)) < 200) {
                $descriptions[] = trim($match);
            } else {
                break;
            }
        }
        if (mb_strlen(implode('. ', $descriptions)) < 200) {
            $descriptions[] = $type1['text'];
        }
        if (mb_strlen(implode('. ', $descriptions)) < 200) {
            $descriptions[] = $set['meta_desc'];
        }
        $meta_desc = bbcode::notags(implode('. ', $descriptions));
        $handle_meta_tags[] = ['name' => 'property', 'value' => 'og:type',             'content' => 'article'];
        $handle_meta_tags[] = ['name' => 'property', 'value' => 'og:url',              'content' => $thread_url];
        $handle_meta_tags[] = ['name' => 'property', 'value' => 'og:title',            'content' => functions::checkout($page_title)];
        $handle_meta_tags[] = ['name' => 'property', 'value' => 'og:description',      'content' => functions::checkout($meta_desc)];
        $handle_meta_tags[] = ['name' => 'property', 'value' => 'og:image',            'content' => functions::checkout($img)];
        unset($descriptions, $img);

        require(ROOTPATH . 'system/header.php');

        // The resulting structure Forum
        $res = true;
        $allow = 0;
        $parent = (int) $type1['refid'];
        while ($parent != 0 && $res) {
            $res = mysql_fetch_assoc(mysql_query('SELECT `type`, `allow`, `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $parent . '" LIMIT 1'));
            if ($res) {
                if ($res['type'] == 'f') {
                    if ($res['refid'] == 0) {
                        $tree[] = ['/forum/#' . functions::makeUrl($res['forum_name']) . '-' . $parent, $res['forum_name']];
                    } else {
                        $tree[] = ['/forum/categories/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']];
                    }
                } else {
                    $tree[] = ['/forum/forums/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']];
                    $allow = intval($res['allow']);
                }
            }
            $parent = $res['refid'];
        }
        $tree[] = ['/forum/', $lng['forum']];
        krsort($tree);

        $breadcrumb = new breadcrumb(1);
        $breadcrumb->add($tree);
        $_breadcrumb = $breadcrumb->out();

        // Counter "Who's the topic?
        if ($user_id) {
            $tpl_data['thread_online_url'] = $thread_url . 'online';
            $tpl_data['online_users'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum-theme-' . $id . '%"'), 0);
            $tpl_data['online_guests'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum-theme-' . $id . '%"'), 0);
        }
        $tpl_data['search_url'] = SITE_URL . '/forum/search';
        $tpl_data['facebook_share_url'] = 'https://www.facebook.com/dialog/share?app_id=' . FB_APP_ID . '&display=popup&href=' . urlencode($thread_url) . '&redirect_uri=' . urlencode($thread_url);

        // 	Counter post topics
        $colmes = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $id . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"')), 0);
        $start = functions::fixStart($start, $colmes, $kmess);
        $max_page = ceil($colmes / $kmess);
        if ($page > $max_page) {
            $page = $max_page;
        }
        // add relative link
        $html_links[] = ['rel' => 'canonical', 'href' => $thread_url . ($page != 1 ? 'page-' . $page . '' : '')];
        if ($page > 1) {
            $html_links[] = ['rel' => 'prev', 'href' => $thread_url . ($page > 2 ? 'page-' . ($page - 1) : '')];
        }
        if ($page < $max_page) {
            $html_links[] = ['rel' => 'next', 'href' => $thread_url . 'page-' . ($page + 1)];
        }
        $tpl_data['pagination'] = ($colmes > $kmess ? functions::display_pagination($thread_url . 'page-', $start, $colmes, $kmess) : '');
        $tpl_data['post_count'] = $colmes;
        $tpl_data['thread_prefix'] = $type1['prefix'];
        $tpl_data['thread_prefix_name'] = $prefixs[$type1['prefix']];
        $tpl_data['thread_name'] = functions::checkout($type1['text']);
        $tpl_data['thread_deleted'] = $type1['thread_deleted'];
        $tpl_data['thread_delete_user'] = $type1['thread_deleted_user'];
        $tpl_data['thread_closed'] = $type1['thread_closed'];
        $tpl_data['has_vote'] = (int) $type1['realid'];
        $tpl_data['can_reply'] = false;

        // Polls
        if ($type1['realid']) {
            if ($id == 14883) {
                $topic_ids = [$id];
            } else {
                $topic_ids = [$id];
            }

            foreach ($topic_ids as $topic_id) {
                $current_thread_url = SITE_URL . '/forum/threads/' . functions::makeUrl($type1['text']) . '.' . $topic_id . '/';

                $can_vote = false;
                if (($user_id && !isset($ban['1']) || !isset($ban['11']) && !$type1['thread_closed'] && $set['mod_forum'] != 3 && $allow != 4) || ($rights >= RIGHTS_ADMIN)) {
                    $can_vote = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote_users` WHERE `user` = "' . $user_id . '" AND `topic`="' . $topic_id . '"'), 0) ? 0 : 1;
                }
                $topic_vote = mysql_fetch_assoc(mysql_query("SELECT `name`, `time`, `count` FROM `cms_forum_vote` WHERE `type`='1' AND `topic`='$topic_id' LIMIT 1"));
                $vote_results = mysql_query("SELECT `id`, `name`, `count` FROM `cms_forum_vote` WHERE `type`='2' AND `topic`='" . $topic_id . "' ORDER BY `id` ASC");

                $tpl_data['votes'][$topic_id]['can_vote'] = $can_vote;
                $tpl_data['votes'][$topic_id]['vote_name'] = functions::checkout($topic_vote['name']);
                $tpl_data['votes'][$topic_id]['vote_count'] = $topic_vote['count'];
                $tpl_data['votes'][$topic_id]['poll_options'] = [];
                while ($vote = mysql_fetch_assoc($vote_results)) {
                    $percent = $topic_vote['count'] ? round(100 * $vote['count'] / $topic_vote['count']) : 0;
                    $tpl_data['votes'][$topic_id]['poll_options'][] = [
                        'id'           => $vote['id'],
                        'text'         => functions::checkout($vote['name']),
                        'count'        => $vote['count'],
                        'html_class'   => ceil($percent / 25),
                        'percent'      => $percent
                    ];
                }
                $tpl_data['votes'][$topic_id]['vote_form_url'] = $current_thread_url . 'vote';
                $tpl_data['votes'][$topic_id]['vote_result_url'] = $thread_url . 'page-' . $page . '?vote_result';
                $tpl_data['votes'][$topic_id]['vote_users_url'] = $current_thread_url . 'vote-users';
                $tpl_data['votes'][$topic_id]['vote_url'] = $thread_url . 'page-' . $page;
                if (!isset($_GET['vote_result']) && $can_vote) {
                    $tpl_data['votes'][$topic_id]['show_vote_result'] = false;
                } else {
                    $tpl_data['votes'][$topic_id]['show_vote_result'] = true;
                }
            }
        }


        $req = mysql_query('SELECT `phonho_posts`.*, `users`.`sex`, `users`.`rights`, `users`.`lastdate`, `users`.`status`, `users`.`datereg`, `users`.`postforum`
            FROM `phonho_posts` LEFT JOIN `users` ON `phonho_posts`.`user_id` = `users`.`id`
            WHERE `phonho_posts`.`refid` = "' . $id . '"' . ($rights >= 7 ? '' : ' AND `phonho_posts`.`post_deleted` != "1"') . '
            ORDER BY `phonho_posts`.`id` ASC LIMIT ' . $start . ', ' . $kmess);
        $i = 1;

        ////////////////////////////////////////////////////////////
        // 	The main list of posts                                 //
        ////////////////////////////////////////////////////////////
        $thread_read_time = 0;
        $thread_read_post = 0;
        $tpl_data['posts'] = array();
        while ($res = mysql_fetch_assoc($req)) {
            $tpl_data['posts'][$res['id']] = [
                'id'                 => $res['id'],
                'time'               => functions::display_date($res['time']),
                'position'           => ($start + $i),
                'author_avatar'      => functions::get_avatar($res['user_id']),
                'author_name'        => $res['from'],
                'author_profile_url' => SITE_URL . '/profile/' . $res['from'] . '.' . $res['user_id'] . '/',
                'author_html_class'  => 'user_' . $res['rights'],
                'author_online'      => (SYSTEM_TIME - 300 <= $res['lastdate']),
                'author_postforum'   => $res['postforum'],
                'author_group'       => (isset($user_rights[$res['rights']]) ? $user_rights[$res['rights']] : ''),
                'author_status'      => (empty($res['status']) ? '' : functions::checkout($res['status'])),
                'html_class'         => ($res['post_deleted'] ? ' bg-notif' : ''),
                'content'            => functions::checkout($res['text'], 3, 1, 1),
                'edited'             => (int) $res['tedit'],
                'edit_user'          => $res['edit'],
                'edit_time'          => functions::display_date($res['tedit']),
                'has_attach'         => false,
                'show_sub_info'      => false
            ];

            $menu = array();
            // If there is an attached file, print it Description
            $freq = mysql_query('SELECT `id`, `filename`, `dlcount`, `time` FROM `cms_forum_files` WHERE `post` = "' . $res['id'] . '"');
            if (mysql_num_rows($freq) > 0) {
                $fres = mysql_fetch_assoc($freq);
                $fls = round(@filesize(ROOTPATH . 'files/forum/attach/' . $fres['filename']) / 1024, 2);
                $tpl_data['posts'][$res['id']]['has_attach'] = true;
                $tpl_data['posts'][$res['id']]['attach_url'] = SITE_URL . '/forum/files/' . $fres['id'] . '/';
                $tpl_data['posts'][$res['id']]['attach_name'] = $fres['filename'];
                $tpl_data['posts'][$res['id']]['attach_size'] = $fls;
                $tpl_data['posts'][$res['id']]['attach_downloads'] = $fres['dlcount'];
                if ($rights == 9) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/delfile', 'name' => 'Xóa đính kèm'];
                }
            } elseif($res['user_id'] == $user_id && $rights >= 7) {
                $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/addfile', 'name' => $lng['add_file']];
            }

            // Links to edit / delete posts
            if (
                (($rights == 3 || $rights >= 6) && $rights >= $res['rights'])
                || ($res['user_id'] == $user_id && ($start + $i) == $colmes && $res['time'] > SYSTEM_TIME - 300)
                || ($i == 1 && $allow == 2 && $res['user_id'] == $user_id)
            ) {
                $tpl_data['posts'][$res['id']]['show_sub_info'] = true;
                $tpl_data['posts'][$res['id']]['deleted'] = (int) $res['post_deleted'];
                $tpl_data['posts'][$res['id']]['delete_user'] = $res['post_deleted_user'];
                // Service menu post
                if (!$res['post_deleted'] || $rights == 9) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/delete', 'name' => $lng['delete']];
                }
                if (!$res['post_deleted']) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/edit', 'name' => $lng['edit']];
                }
                if ($rights >= 7 && $res['post_deleted']) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/restore', 'name' => $lng['restore']];
                }
                // Shows IP and Useragent
                if ($rights == 3 || $rights >= 6) {
                    $tpl_data['posts'][$res['id']]['ip'] = long2ip($res['ip']);
                    $tpl_data['posts'][$res['id']]['search_ip_url'] = SITE_URL . '/' . $set['admp'] . '/search-ip?ip=' . long2ip($res['ip']);
                    $tpl_data['posts'][$res['id']]['ip_via_proxy'] = ($res['ip_via_proxy'] ? long2ip($res['ip_via_proxy']) : 0);
                    $tpl_data['posts'][$res['id']]['search_ip_via_proxy_url'] = SITE_URL . '/' . $set['admp'] . '/search-ip?ip=' . long2ip($res['ip_via_proxy']);
                    $tpl_data['posts'][$res['id']]['browser'] = functions::checkout($res['soft']);
                }
            }
            $likeCheck = false;
            if ($user_id && $user_id != $res['user_id']) {
                if (!$res['post_deleted']) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/quote', 'name' => 'Quote'];
                }
                $likeCheck = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_likes` WHERE `type`="1" AND `user_like`="'. $user_id .'" AND `sub_id`="' . $res['id'] . '"'), 0);
                if (!$res['post_deleted']) {
                    $menu[] = ['url' => SITE_URL . '/forum/posts/' . $res['id'] . '/like', 'name' => ($likeCheck ? 'Unlike' : 'Like')];
                }
            }
            $tpl_data['posts'][$res['id']]['menu'] = $menu;
            /* Show list users like post */
            $likeCount = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_likes` WHERE `type`="1" AND `sub_id`="' . $res['id'] . '"'), 0);
            $likes = '';
            if ($likeCount) {
                $like_users = array();
                /* You */
                if ($likeCheck) {
                    $likes .= 'Bạn';
                }
                if (($likeCount == 1 && !$likeCheck) || $likeCount > 1) {
                    /* Other User */
                    $lreq = mysql_query('SELECT `cms_likes`.`user_like`, `users`.`account` FROM `cms_likes` LEFT JOIN `users` ON `users`.`id` = `cms_likes`.`user_like` WHERE `cms_likes`.`type`="1" AND `cms_likes`.`sub_id`="'.$res['id'].'" AND `cms_likes`.`user_like` != "'. $user_id .'" AND `users`.`account` is not null ORDER BY `cms_likes`.`id` DESC LIMIT 2');
                    while ($lres = mysql_fetch_assoc($lreq)) {
                        $like_users[] = '<a href="' . SITE_URL . '/profile/' . $lres['account'] . '.' . $lres['user_like'] . '/">'. htmlspecialchars($lres['account']) .'</a>';
                    }
                    if (!empty($like_users)) {
                        if ($likeCount == 1 || ($likeCount == 2 && $likeCheck)) {
                            $likes .= ($likeCheck ? ' và ' : '') . implode('', $like_users);
                        } elseif ($likeCount == 2 || ($likeCount == 3 && $likeCheck)) {
                            $likes .= ($likeCheck ? ', ' :'') . implode(' và ', $like_users);
                        } else {
                            $likes .= ($likeCheck ? ', ' : '') . implode(', ', $like_users) . ' và <a href="' . SITE_URL . '/forum/posts/' . $res['id'] . '/likes">' . ($likeCheck ? $likeCount - 3 : $likeCount - 2) . ' người khác</a>';
                        }
                    }
                }
            }
            $tpl_data['posts'][$res['id']]['likes'] = (empty($likes) ? '' : $likes . ' thích điều này');
            // for mark reading topic
            if (($start + $i) == $colmes) {
                $thread_read_time = $type1['time'];
            } else {
                $thread_read_time = $res['time'];
            }
            $thread_read_post = $res['id'];
            ++$i;
        }
        // Fixing the fact reading Topic
        if ($user_id) {
            $req_r = mysql_query('SELECT `time` FROM `cms_forum_rdm` WHERE `topic_id` = "' . $id . '" AND `user_id` = "' . $user_id . '" LIMIT 1');
            if (mysql_num_rows($req_r)) {
                $res_r_time = mysql_result($req_r, 0);
                if ($thread_read_time > $res_r_time) {
                    mysql_query('UPDATE `cms_forum_rdm` SET `post_id` = "' . $thread_read_post . '", `time` = "' . $thread_read_time . '" WHERE `topic_id` = "' . $id . '" AND `user_id` = "' . $user_id . '"');
                }
            } else {
                mysql_query('INSERT INTO `cms_forum_rdm` SET `topic_id` = "' . $id . '", `post_id` = "' . $thread_read_post . '", `user_id` = "' . $user_id . '", `time` = "' . $thread_read_time . '"');
            }
        }
        $tpl_data['forum_unread_count'] = counters::forum_new();
        $tpl_data['forum_unread_url'] = SITE_URL . '/forum/find-new?type=unread';

        // The field "Write"
        if (($user_id && !isset($ban['1']) && !isset($ban['11']) && !$type1['thread_closed'] && $set['mod_forum'] != 3 && $allow != 4) || ($rights >= 7)) {
            $tpl_data['can_reply'] = true;
            $tpl_data['reply_form_action'] = $thread_url . 'reply';
            $tpl_data['bbcode_editor'] = bbcode::auto_bb('form', 'msg');
        }
        $tpl_data['thread_tags'] = (empty($type1['soft']) ? '' : functions::show_tags($type1['soft'], 1));

        // 	Links to leading management theme
        $tpl_data['thread_moder_menu'] = [];
        if ($rights == 3 || $rights >= 6) {
            if ($type1['realid']) {
                $tpl_data['thread_moder_menu'][] = [
                    'name'  => $lng['edit_vote'],
                    'value' => 'vote-edit'
                ];
                $tpl_data['thread_moder_menu'][] = [
                    'name'  => $lng['delete_vote'],
                    'value' => 'vote-delete'
                ];
            } else {
                $tpl_data['thread_moder_menu'][] = [
                    'name'  => $lng['add_vote'],
                    'value' => 'vote-add'
                ];
            }
            $tpl_data['thread_moder_menu'][] = [
                'name'  => $lng['topic_edit'],
                'value' => 'edit'
            ];
            if ($rights > RIGHTS_ADMIN) {
                // Delete - Restore topic
                if ($type1['thread_deleted'] == 1) {
                    $tpl_data['thread_moder_menu'][] = [
                        'name'  => $lng['topic_restore'],
                        'value' => 'restore'
                    ];
                }
                $tpl_data['thread_moder_menu'][] = [
                    'name'  => $lng['topic_delete'],
                    'value' => 'delete'
                ];
            }
            $tpl_data['thread_moder_menu'][] = [
                'name'  => $lng['topic_move'],
                'value' => 'move'
            ];
        }

        // similar topic
        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`
            WHERE MATCH (`text`) AGAINST ("'. mysql_real_escape_string($type1['text']) .'" IN BOOLEAN MODE)
            AND `id` != "' . $id . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0);
        $tpl_data['similar_threads'] = [];
        if ($total) {
            $req = mysql_query('SELECT `id`,`text`, MATCH (`text`) AGAINST ("' . mysql_real_escape_string($type1['text']) . '" IN BOOLEAN MODE) as `rel`
                FROM `phonho_threads`
                WHERE MATCH (`text`) AGAINST ("'. mysql_real_escape_string($type1['text']) .'" IN BOOLEAN MODE) AND `id` != "' . $id . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"') . '
                ORDER BY `rel` DESC
                LIMIT 5
            ');
            while ($res = mysql_fetch_assoc($req)) {
                $tpl_data['similar_threads'][] = [
                    'url' => SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $res['id'] . '/',
                    'name' => functions::checkout($res['text'])
                ];
            }
        }

        $tpl_data['readedUsers'] = [];
        $query = mysql_query('SELECT `users`.`id`, `users`.`account`, `users`.`rights` FROM `cms_forum_rdm`
            INNER JOIN `users` ON `users`.`id` = `cms_forum_rdm`.`user_id`
            WHERE `topic_id` = "' . $id . '"
            ORDER BY `cms_forum_rdm`.`time` DESC
        ');
        while ($res = mysql_fetch_assoc($query)) {
            $tpl_data['readedUsers'][] = [
                'name'        => $res['account'],
                'profile_url' => SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/',
                'html_class'  => 'user_' . $res['rights'],
            ];
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_topic_deleted'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
        $tpl_data['back_text'] = $lng['to_forum'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}