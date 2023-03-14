<?php
defined('_MRKEN_CMS') or die('Error: restricted access');


$headmod = 'forum';
$page_title = $lng['forum'];
require(ROOTPATH . 'system/header.php');
$breadcrumb = new breadcrumb(0, 1);
$breadcrumb->add($lng['forum']);
$_breadcrumb = $breadcrumb->out();

$tpl_data['search_url'] = SITE_URL . '/forum/search';
$tpl_data['forum_unread_count'] = counters::forum_new();
$tpl_data['forum_unread_url'] = SITE_URL . '/forum/find-new?type=unread';

$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "f"'), 0);
$tpl_data['categories'] = array();
if ($total) {
    $req = mysql_query('SELECT `id`, `forum_name`, `forum_desc` FROM `phonho_forums` WHERE `type`="f" ORDER BY `realid`');
    while ($res = mysql_fetch_array($req)) {
        $tpl_data['categories'][$res['id']] = array(
            'id'          => $res['id'],
            'html_id'     => functions::makeUrl($res['forum_name']) . '-' . $res['id'],
            'name'        => functions::checkout($res['forum_name']),
            'url'         => SITE_URL . '/forum/categories/' . functions::makeUrl($res['forum_name']) . '.' . $res['id'] . '/',
            'description' => (empty($res['forum_desc']) ? '' : functions::checkout($res['forum_desc'])),
            'forums'      => array()
        );
        $count = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type`="r" and `refid`="' . $res['id'] . '"'), 0);
        if ($count) {
            $req2 = mysql_query('SELECT `id`, `forum_name`, `forum_desc` FROM `phonho_forums` WHERE `type`="r" AND `refid` = "' . $res['id'] . '" ORDER BY `realid`');
             while ($res2 = mysql_fetch_assoc($req2)) {
                $tpl_data['categories'][$res['id']]['forums'][] = array(
                    'id'          => $res2['id'],
                    'name'        => functions::checkout($res2['forum_name']),
                    'url'         => SITE_URL . '/forum/forums/' . functions::makeUrl($res2['forum_name']).'.' . $res2['id'] . '/',
                    'description' => (empty($res2['forum_desc']) ? '' : functions::checkout($res2['forum_desc']))
                );
            }
        }
    }
}

$tpl_data['online_users'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum%"'), 0);
$tpl_data['online_guests'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > ' . (SYSTEM_TIME - 300) . ' AND `place` LIKE "forum%"'), 0);
$tpl_data['forum_online_url'] = SITE_URL . '/forum/online';

$tpl_file = 'forum::forum';