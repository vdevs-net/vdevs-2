<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require_once(ROOTPATH . 'system/header.php');
$tpl_file = 'messages::list';
$breadcrumb = new breadcrumb();
$breadcrumb->add('/messages/', $lng['mail']);
$breadcrumb->add($lng['new_messages']);
$_breadcrumb = $breadcrumb->out();

$total = mysql_result(mysql_query('SELECT COUNT(DISTINCT `user_id`) FROM `cms_mail` WHERE `from_id`="' . $user_id . '" AND `read`="0" AND `sys` = "0"'), 0);

$tpl_data['total'] = $total;
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('new?page=', $start, $total, $kmess) : '');
$tpl_data['items'] = [];
if($total) {
	// Grupiruem for contacts
    $query = mysql_query('SELECT `users`.`id`, `users`.`account`, `users`.`rights`, `users`.`lastdate`, `users`.`status`, `users`.`browser`, `users`.`ip`, `users`.`ip_via_proxy`, MAX(`cms_mail`.`time`) AS `time`, COUNT(*) as `count`
        FROM `cms_mail`
        LEFT JOIN `users` ON `cms_mail`.`user_id` = `users`.`id`
        WHERE `cms_mail`.`from_id` = "' . $user_id . '"
        AND `cms_mail`.`delete` != "' . $user_id . '"
        AND `cms_mail`.`sys` = "0"
        AND `cms_mail`.`read` = "0"
        GROUP BY `cms_mail`.`user_id`
        ORDER BY `time` DESC
        LIMIT ' . $start . ', ' . $kmess);
	while ($row = mysql_fetch_assoc($query)) {
		$subtext = '<a href="' . SITE_URL . '/messages/' . $row['account'] . '.' . $row['id'] . '/">' . $lng['correspondence'] . '</a>';
		$count_message = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_mail` WHERE ((`user_id` = "' . $row['id'] . '" AND `from_id` = "' . $user_id . '") OR (`user_id` = "' . $user_id . '" AND `from_id` = "' . $row['id'] . '")) AND `delete` != "' . $user_id . '";'), 0);
		$arg = array(
		  'header' => '(' . $count_message . ($row['count'] ? '/<span class="red">+' . $row['count'] . '</span>' : '') . ') ' . functions::display_date($row['time']),
		  'sub' => $subtext
		);
        $tpl_data['items'][] = [
            'html_class' => 'menu',
            'content' => functions::display_user($row, $arg)
        ];
	}
}