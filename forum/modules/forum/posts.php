<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $req = mysql_query('SELECT `refid` FROM `phonho_posts` WHERE `id` = "' . $id . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ' LIMIT 1');
    if (mysql_num_rows($req)) {
        $refid = mysql_result($req, 0);
        $them_req = mysql_query('SELECT `text` FROM `phonho_threads` WHERE `id` = "' . $refid . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` != "1"') . ' LIMIT 1');
        if (mysql_num_rows($them_req)) {
            $them = mysql_result($them_req, 0);
            $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $refid . '" AND `id` <= "' . $id . '"' . ($rights >= 7 ? '' : ' AND `post_deleted` != "1"') . ';'), 0) / $kmess);
            header('Location: ' . SITE_URL . '/forum/threads/' . functions::makeUrl($them) . '.' . $refid . '/page-' . $_page .  ($allow_js_scroll ? '?st=' : '#') . 'post' . $id); exit;
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_post_deleted'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}