<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights >= 7) {
        // Проверяем, существует ли тема
        $req = mysql_query('SELECT `phonho_threads`.`refid`, `phonho_threads`.`text`, `phonho_forums`.`forum_name` as `ref_name` FROM `phonho_threads` LEFT JOIN `phonho_forums` ON `phonho_forums`.`id`=`phonho_threads`.`refid` WHERE `phonho_threads`.`id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);
            $thread_url = '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            if (IS_POST) {
                $del = isset($_POST['del']) ? abs(intval($_POST['del'])) : 0;
                if ($del && $rights == 9) {
                    // remove topic
                    $forum = new forum();
                    $forum->del_topic($id);
                    mysql_query('DELETE FROM `phonho_threads` WHERE `id`="' . $id . '"');
                } else {
                    // hide the topic
                    mysql_query('UPDATE `phonho_threads` SET `thread_deleted` = "1", `thread_deleted_user` = "' . $login . '" WHERE `id` = "' . $id . '"');
                    mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "1", `post_deleted_user` = "' . $login . '" WHERE `refid` = "' . $id . '"');
                    mysql_query('UPDATE `cms_forum_files` SET `del` = "1" WHERE `topic` = "' . $id . '"');
                }
                header('Location: ' . SITE_URL . '/forum/forums/' . functions::makeUrl($res['ref_name']) . '.' . $res['refid'] . '/'); exit;
            } else {
                require(ROOTPATH . 'system/header.php');

                $breadcrumb = new breadcrumb(0, 1);
                $breadcrumb->add($thread_url, $res['text']);
                $breadcrumb->add($lng['topic_delete']);
                $_breadcrumb = $breadcrumb->out();

                $tpl_file = 'page.confirm';
                $tpl_data['form_action'] = 'delete';
                $tpl_data['confirm_text'] = $lng['delete_confirmation'];
                $tpl_data['cancel_url'] = $thread_abs_url;
                $tpl_data['confirm_options'] = [
                    [
                        'title' => 'Xóa vĩnh viễn',
                        'items' => [
                            [
                                'type'    => 'checkbox',
                                'name'    => 'del',
                                'value'   => 1,
                                'explain' => 'Chủ đề không thể khôi phục nếu bạn chọn lựa chọn này!'
                            ]
                        ]
                    ]
                ];
            }
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}