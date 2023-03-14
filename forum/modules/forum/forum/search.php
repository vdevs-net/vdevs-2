<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$headmod = 'forumsearch';


$search = isset($_GET['search']) ? rawurldecode(functions::checkin($_GET['search'])) : false;
if (isset($_GET['search']) && !$search) {
    header('Location: search'); exit;
}


$search_t = isset($_GET['t']);
if ($search) {
    $search = str_replace('%', '', $search);
}
$page_title = ($search ? $search . ' - ' : '') . $lng['search_forum'];
require(ROOTPATH . 'system/header.php');
if ($search) {
    $breadcrumb = new breadcrumb(1);
    $breadcrumb->add('/forum/', $lng['forum']);
    $breadcrumb->add('/forum/search', $lng['search']);
} else {
    $breadcrumb = new breadcrumb(0, 1);
    $breadcrumb->add('/forum/', $lng['forum']);
    $breadcrumb->add($lng['search']);
}
$_breadcrumb = $breadcrumb->out();

$tpl_data['total'] = 0;
$tpl_data['results'] = array();
$tpl_data['show_result'] = false;
$tpl_data['form_action'] = SITE_URL . '/forum/search';
$tpl_data['input_search'] = functions::checkout($search);
$tpl_data['input_search_t'] = $search_t;
$tpl_data['pagination'] = '';

// Check for errors
$error = $search && mb_strlen($search) < 4 || mb_strlen($search) > 64 ? true : false;

if ($search && !$error) {
    $tpl_data['show_result'] = true;
    // Conclusions The results of the query
    $array = explode(' ', $search);
    $array = array_diff($array, array(''));
    $count = count($array);
    $query = mysql_real_escape_string($search);
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_' . ($search_t ? 'threads' : 'posts') . '` WHERE MATCH (`text`) AGAINST ("' . $query . '" IN BOOLEAN MODE)' . ($rights >= 7 ? '' : ' AND `' . ($search_t ? 'thread' : 'post') . '_deleted` != "1"')), 0);
    if ($total) {
        $tpl_data['total'] = $total;
        if ($total > $kmess) {
            $tpl_data['pagination'] = functions::display_pagination('search?search=' . urlencode($search) . ($search_t ? '&t=1' : '') . '&page=', $start, $total, $kmess);
        }
        $req = mysql_query('SELECT *, MATCH (`text`) AGAINST ("' . $query . '" IN BOOLEAN MODE) as `rel`
            FROM `phonho_' . ($search_t ? 'threads' : 'posts') . '`
            WHERE MATCH (`text`) AGAINST ("' . $query . '" IN BOOLEAN MODE)' . ($rights >= 7 ? '' : ' AND `' . ($search_t ? 'thread' : 'post') . '_deleted` != "1"') . '
            ORDER BY `rel` DESC
            LIMIT ' . $start . ', ' . $kmess . '
        ');
        while ($res = mysql_fetch_assoc($req)) {
            if ($search_t) {
                // Search topic title
                $res_p = mysql_fetch_assoc(mysql_query('SELECT `id`, `text` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '" ORDER BY `id` ASC LIMIT 1'));
                $tpl_data['results'][$res['id']]['thread_url'] = SITE_URL . '/forum/threads/'. functions::makeUrl($res['text']) . '.' . $res['id'] . '/';
                $res['text'] = functions::checkout($res['text']);
                foreach ($array as $val) {
                    $res['text'] = ReplaceKeywords($val, $res['text']);
                }
                $text = $res_p['text'];
                $tags = $res['soft'];
                $tpl_data['results'][$res['id']]['thread_name'] = $res['text'];
            } else {
                // Only search in the text
                $res_t = mysql_fetch_assoc(mysql_query('SELECT `id`,`text`,`soft` FROM `phonho_threads` WHERE `id` = "' . $res['refid'] . '" LIMIT 1'));
                $text = $res['text'];
                $tags = $res_t['soft'];
                $tpl_data['results'][$res['id']]['thread_url'] = SITE_URL . '/forum/posts/' . $res['id'] . '/';
                $tpl_data['results'][$res['id']]['thread_name'] = functions::checkout($res_t['text']);
            }
            $tpl_data['results'][$res['id']]['tags'] = '';
			if (!empty($tags)) {
				$tags = functions::show_tags($tags);
				foreach ($array as $val) {
                    $tags = ReplaceKeywords($val, $tags);
                }
                $tpl_data['results'][$res['id']]['tags'] = $tags;
			}
            $tpl_data['results'][$res['id']]['author'] = $res['from'];
            $tpl_data['results'][$res['id']]['time'] = functions::display_date($res['time']);
            foreach ($array as $srch) {
                if (($pos = mb_strpos(mb_strtolower($text), mb_strtolower(str_replace('*', '', $srch)))) !== false) break;
            }
            if (!isset($pos) || $pos < 50){
                $pos = 50;
                $dbf = false;
            } else {
                $dbf = true;
            }
            $text2 = bbcode::notags($text);
            $text2 = preg_replace('/([\r\n]|\r\n)/is', ' ', $text2);
            $text2 = mb_substr($text2, ($pos - 50), 100);
            $text2 = htmlspecialchars($text2);
            if (!$search_t) {
                foreach ($array as $val) {
                    $text2 = ReplaceKeywords($val, $text2);
                }
            }
            $tpl_data['results'][$res['id']]['description'] = ($dbf ? '...' : '') . $text2 . (mb_strlen($text) > 150 ? '...' : '');
        }
    }
}
$tpl_data['error'] = ($error ? functions::display_error($lng['error_wrong_lenght']) : '');

$tpl_file = 'forum::forum.search';
