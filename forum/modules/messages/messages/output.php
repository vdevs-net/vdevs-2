<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require_once(ROOTPATH . 'system/header.php');
$breadcrumb = new breadcrumb();
$breadcrumb->add('/messages/', $lng['mail']);
$breadcrumb->add($lng['sent_messages']);
$_breadcrumb = $breadcrumb->out();

$tpl_file = 'messages::list';

$total = mysql_result(mysql_query('
    SELECT COUNT(DISTINCT `from_id`)
    FROM `cms_mail`
    WHERE `user_id` = "' . $user_id . '"
    AND `delete` != "' . $user_id . '" AND `sys` = "0"'), 0);

$tpl_data['total'] = $total;
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('output?page=', $start, $total, $kmess) : '');
$tpl_data['items'] = [];
if ($total) {
    $req = mysql_query('SELECT `users`.*, MAX(`cms_mail`.`time`) AS `time`
        FROM `cms_mail`
	    LEFT JOIN `users` ON `cms_mail`.`from_id`=`users`.`id`
		WHERE `cms_mail`.`user_id`="' . $user_id . '"
		AND `cms_mail`.`delete`!="' . $user_id . '"
		AND `cms_mail`.`sys`="0"
		GROUP BY `cms_mail`.`from_id`
		ORDER BY MAX(`cms_mail`.`time`) DESC
		LIMIT ' . $start . ', ' . $kmess);
    while ($row = mysql_fetch_assoc($req)) {
        $count_message = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_mail`
            WHERE `user_id`="' . $user_id . '"
            AND `from_id`="' . $row['id'] . '"
            AND `delete`!="' . $user_id . '"
            AND `sys`!="1"
        '), 0);

        $last_msg = mysql_fetch_assoc(mysql_query('SELECT *
            FROM `cms_mail`
            WHERE `from_id`="' . $row['id'] . '"
            AND `user_id` = "' . $user_id . '"
            AND `delete` != "' . $user_id . '"
            ORDER BY `id` DESC
            LIMIT 1'));
        if (mb_strlen($last_msg['text']) > 500) {
            $text = mb_substr($last_msg['text'], 0, 500);
            $text = functions::checkout($text, 1, 1, 1);
            $text = bbcode::notags($text);
            $text .= '...';
        } else {
            $text = functions::checkout($last_msg['text'], 1, 1, 1);
        }

        $arg = array(
            'header' => '<span class="gray">(' . functions::display_date($last_msg['time']) . ')</span>',
            'body'   => '<div style="font-size: small" class="text">' . $text . '</div>',
            'sub'    => '<div class="mv"><a href="' . SITE_URL . '/messages/' . $row['account'] . '.' . $row['id'] . '/"><b>' . $lng['correspondence'] . '</b></a> (' . $count_message . ')</div>',
            'iphide' => 1
        );
        $tpl_data['items'][] = [
            'html_class' => ($last_msg['read'] ? 'menu' : 'gmenu'),
            'content'    => functions::display_user($row, $arg) 
        ];
    }
}
