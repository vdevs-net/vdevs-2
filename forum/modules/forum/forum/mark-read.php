<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

// Select all topics read
if ($user_id) {
    $req = mysql_query('SELECT `phonho_threads`.`id`, `cms_forum_rdm`.`topic_id`
        FROM `phonho_threads` LEFT JOIN `cms_forum_rdm` ON `phonho_threads`.`id` = `cms_forum_rdm`.`topic_id` AND `cms_forum_rdm`.`user_id` = "' . $user_id . '"
        WHERE `cms_forum_rdm`.`topic_id` IS Null OR `phonho_threads`.`time` > `cms_forum_rdm`.`time`');
    $insert = [];
    while ($res = mysql_fetch_assoc($req)) {
        $post_id = mysql_result(mysql_query('SELECT `id` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '" ORDER BY `id` DESC LIMIT 1'), 0);
        $insert[] = '("' . $res['id'] . '", "' . $post_id . '", "' . $user_id . '", "' . SYSTEM_TIME . '")';
    }
    if ($insert) {
        mysql_query('INSERT INTO `cms_forum_rdm` (`topic_id`, `post_id`, `user_id`, `time`) VALUES ' . implode(', ', $insert) . ' ON DUPLICATE KEY UPDATE `post_id` = VALUES(`post_id`), `time` = VALUES(`time`)');
    }
    $tpl_file = 'page.success';
    $tpl_data['page_content'] = $lng['unread_reset_done'];
    $tpl_data['back_url'] = SITE_URL . '/forum/';
    $tpl_data['back_text'] = $lng['to_forum'];
}