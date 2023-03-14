<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$fid = isset($_GET['fid']) ? abs(intval($_GET['fid'])) : 0;
if ($fid) {
    $req = mysql_query('SELECT `file_name` FROM `cms_mail` WHERE (`user_id`="' . $user_id . '" OR `from_id`="' . $user_id . '") AND `id` = "' . $fid . '" AND `file_name` != "" AND `delete` != "' . $user_id . '" LIMIT 1');
    if (mysql_num_rows($req)) {
        $res = mysql_fetch_assoc($req);
        if(file_exists(ROOTPATH . 'files/messages/' . $res['file_name'])) {
            mysql_query('UPDATE `cms_mail` SET `count` = (`count` + 1) WHERE `id` = "' . $fid . '"');
            Header('Location: ' . SITE_URL . '/files/messages/' . $res['file_name']);
        }
    }
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = functions::display_error($lng['file_does_not_exist']);
} else {
    $page_title = $lng['mail'] . ' | ' . $lng['files'];
    require_once(ROOTPATH . 'system/header.php');
    $tpl_file = 'messages::list';
    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/messages/', $lng['mail']);
    $breadcrumb->add($lng['files']);
    $_breadcrumb = $breadcrumb->out();

    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `delete`!='$user_id' AND `file_name`!=''"), 0);
    $tpl_data['total'] = $total;
    $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('files?page=', $start, $total, $kmess) : '');
    $tpl_data['items'] = [];
    if ($total) {
        $req = mysql_query("SELECT `cms_mail`.*, `users`.`account`
            FROM `cms_mail`
            LEFT JOIN `users` ON `cms_mail`.`user_id`=`users`.`id`
    	    WHERE (`cms_mail`.`user_id`='$user_id' OR `cms_mail`.`from_id`='$user_id')
    	    AND `cms_mail`.`delete`!='$user_id'
    	    AND `cms_mail`.`file_name`!=''
    	    ORDER BY `cms_mail`.`time` DESC
    	    LIMIT " . $start . "," . $kmess);
        while ($row = mysql_fetch_assoc($req)) {
            $tpl_data['items'][] = [
                'html_class' => 'menu',
                'content'    => '<a href="' . SITE_URL . '/profile/' . $row['account'] . '.' . $row['user_id'] . '/"><b>' . $row['account'] . '</b></a>:: <a href="files?fid=' . $row['id'] . '" class="noPusher">' . $row['file_name'] . '</a> (' . formatsize($row['size']) . ') (' . $row['count'] . ')'
            ];
        }
    }
}