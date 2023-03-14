<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = $lng['news'];
require(ROOTPATH . 'system/header.php');

$tpl_file = 'news::news';

$breadcrumb = new breadcrumb();
$breadcrumb->add($lng['news']);
$_breadcrumb = $breadcrumb->out();

$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `news`'), 0);
$tpl_data['total'] = $total;
$tpl_data['pagination'] = '';
$tpl_data['items'] = array();
$tpl_data['add_news_url']   = SITE_URL . '/news/add';
$tpl_data['clean_news_url'] = SITE_URL . '/news/clean';
if ($total) {
    $req = mysql_query('SELECT `news`.*, `phonho_threads`.`text` as `tname` FROM `news` LEFT JOIN `phonho_threads` ON `phonho_threads`.`id`=`news`.`kom` ORDER BY `time` DESC LIMIT ' . $start . ', ' . $kmess);
    while ($res = mysql_fetch_array($req)) {
        $tpl_data['items'][$res['id']] = array(
            'title'         => functions::checkout($res['name']),
            'author'        => $res['avt'],
            'time'          => functions::display_date($res['time']),
            'content'       => functions::checkout($res['text'], 1, 1, 1),
            'comment_url'   => '',
            'comment_count' => 0,
            'edit_url'      => '',
            'delete_url'    => ''
        );
        if ($res['kom']) {
            $comments = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['kom'] . '"'), 0) - 1;
            if ($comments >= 0) {
                $tpl_data['items'][$res['id']]['comment_url'] = SITE_URL . '/forum/threads/' . functions::makeUrl($res['tname']) . '.' . $res['kom'] . '/';
                $tpl_data['items'][$res['id']]['comment_count'] = $comments;
            }
        }
        if ($rights >= 6) {
            $tpl_data['items'][$res['id']]['edit_url'] = SITE_URL . '/news/' . $res['id'] . '/edit';
            $tpl_data['items'][$res['id']]['delete_url'] = SITE_URL . '/news/' . $res['id'] . '/delete';
        }
    }
    if ($total > $kmess) {
        $tpl_data['pagination'] = functions::display_pagination(SITE_URL . '/news/page-', $start, $total, $kmess);
    }
}