<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $type = mysql_query('SELECT `refid`, `forum_name` FROM `phonho_forums` WHERE `id`= "' . $id . '" AND `type` = "f" LIMIT 1');
    if (mysql_num_rows($type)) {
        $type1 = mysql_fetch_assoc($type);
        if ($type1['refid'] == 0) {
            header('Location: ' . SITE_URL . '/forum/#' . functions::makeUrl($type1['forum_name']) . '-' . $id); exit;
        }
        $page_title = $type1['forum_name'];
        $headmod = 'forum-category-' . $id;
        require(ROOTPATH . 'system/header.php');
        // The resulting structure Forum
        $res = true;
        $parent = (int) $type1['refid'];
        while ($parent != 0 && $res) {
            $res = mysql_fetch_assoc(mysql_query('SELECT `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $parent . '" AND `type` ="f" LIMIT 1'));
            if ($res) {
                $tree[] = array('/forum/categories/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']);
                $parent = (int) $res['refid'];
            }
        }
        $tree[] = array('/forum/', $lng['forum']);
        krsort($tree);
        $tree[] = array($type1['forum_name']);
        $breadcrumb = new breadcrumb(0, 1);
        $breadcrumb->add($tree);
        $_breadcrumb = $breadcrumb->out();

        $tpl_data['search_url'] = SITE_URL . '/forum/search';
        $tpl_data['forum_unread_count'] = counters::forum_new();
        $tpl_data['forum_unread_url'] = SITE_URL . '/forum/find-new?type=unread';

        $tpl_data['forums'] = array();
        $req = mysql_query('SELECT `id`, `forum_name`, `forum_desc` FROM `phonho_forums` WHERE `type`="r" AND `refid`="' . $id . '" ORDER BY `realid`');
        $total = mysql_num_rows($req);
        if ($total) {
            while ($res = mysql_fetch_assoc($req)) {
                $coltem = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `refid` = "' . $res['id'] . '"' . ($rights >= 7 ? '' : ' AND `thread_deleted` = "0"')), 0);
                $tpl_data['forums'][] = array(
                    'id'           => $res['id'],
                    'name'         => functions::checkout($res['forum_name']),
                    'url'          => SITE_URL . '/forum/forums/' . functions::makeUrl($res['forum_name']) . '.' . $res['id'] . '/',
                    'description'  => (empty($res['forum_desc']) ? '' : functions::checkout($res['forum_desc'])),
                    'thread_count' => $coltem
                );
            }
        }
        $tpl_file = 'forum::categories';
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}
