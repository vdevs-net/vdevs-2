<?php
define('_MRKEN_CMS', 1);
require_once ('../system/core.php');
header('content-type: application/rss+xml');
echo '<?xml version="1.0" encoding="utf-8"?>' .
     '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>' .
     '<title>' . htmlspecialchars($set['copyright']) . ' | News</title>' .
     '<link>' . SITE_URL . '</link>' .
     '<description>News</description>' .
     '<language>vi-VN</language>';

// Библиотека
$req = mysql_query('SELECT `id`, `text`, `from`, `time` FROM `phonho_threads` WHERE `thread_deleted` = "0" ORDER BY `id` DESC LIMIT 15');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_array($req)) {
        $post = mysql_result(mysql_query('SELECT `text` FROM `phonho_posts` WHERE `refid` = "' . $res['id'] . '" ORDER BY `id` ASC LIMIT 1'), 0);
		// get description
		$matches = preg_split('#(\r\n|[\r\n]|\.\s)#', $post);
		$description = '';
		foreach($matches as $match){
			if(mb_strlen($description) < 200) $description .= $match.' ';
			else break;
		}
		$description = htmlspecialchars(functions::checkout($description,2,2));
        echo '<item>' .
             '<title>Forum: ' . htmlspecialchars($res['text']) . '</title>' .
             '<link>' . SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']).'.' . $res['id'] . '/</link>' .
             '<author>' . htmlspecialchars(htmlspecialchars($res['from'])) . '</author>' .
             '<description>' . $description .'</description>' .
             '<pubDate>' . date('r', $res['time']) . '</pubDate>' .
             '</item>';
    }
}
echo '</channel></rss>';