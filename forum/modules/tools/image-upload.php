<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Upload ảnh';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/tools/', 'Công cụ');
$breadcrumb->add('Upload ảnh');
$_breadcrumb = $breadcrumb->out();


$tpl_file = 'tools::image-upload';
$count = ($user_id ? mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_images` WHERE `user_id` = "' . $user_id . '"'), 0) : 0);
$total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_images`'), 0);
$tpl_data['my_images'] = $count;
$tpl_data['total'] = $total;
$tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('page-', $start, $total, $kmess) : '');
$tpl_data['items'] = [];

if ($total) {
    $req = mysql_query('SELECT `cms_images`.*, `users`.`account`, `users`.`rights` FROM `cms_images`
        LEFT JOIN `users` ON `users`.`id` = `cms_images`.`user_id`
        ORDER BY `time` DESC LIMIT ' . $start . ', ' . $kmess);
    while($res = mysql_fetch_assoc($req)) {
        $tpl_data['items'][] = [
            'details_url'         => 'details?id=' . $res['id'],
            'thumb_src'           => preg_replace('|^https?:|', '', functions::imgurSize($res['link'], 's')),
            'uploader_id'         => $res['user_id'],
            'uploader'            => $res['account'],
            'uploader_html_class' => 'user_' . $res['rights'],
            'upload_time'         => functions::display_date($res['time']),
            'file_size'           => round($res['size'] / 1024, 1)
        ];
    }
}