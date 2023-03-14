<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Upload ảnh';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/tools/', 'Công cụ');
$breadcrumb->add('/tools/image-upload/', 'Upload ảnh');
$breadcrumb->add('Ảnh thành viên');
$_breadcrumb = $breadcrumb->out();

$id = $id ? $id : $user_id;
if ($id) {
    $tUser = false;
    if ($id == $user_id) {
        $tUser = array(
            'account' => $datauser['account'],
            'rights'  => $datauser['rights']
        );
    } else {
        $req = mysql_query('SELECT `account`, `rights` FROM `users` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $tUser = mysql_fetch_assoc($req);
        }
    }
    if ($tUser) {
        $tpl_file = 'tools::image-upload.files';
        $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_images` WHERE `user_id` = "' . $id . '"'), 0);
        $tpl_data['total'] = $total;
        $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('files?id=' . $id . '&page=', $start, $total, $kmess) : '');
        $tpl_data['user_name'] = $tUser['account'];
        $tpl_data['user_profile_url'] = SITE_URL . '/profile/' . $tUser['account'] . '.' . $id . '/';
        $tpl_data['user_html_class'] = 'user_' . $tUser['rights'];
        if ($total) {
            $req = mysql_query('SELECT `id`, `link`, `time`, `size` FROM `cms_images` WHERE `user_id` = "' . $id . '" ORDER BY `time` DESC LIMIT ' . $start . ', ' . $kmess);
            while($res = mysql_fetch_assoc($req)) {
                $tpl_data['items'][] = [
                    'details_url' => 'details?id=' . $res['id'],
                    'thumb_src' => preg_replace('|^https?:|', '', functions::imgurSize($res['link'], 's')),
                    'upload_time'         => functions::display_date($res['time']),
                    'file_size'           => round($res['size'] / 1024, 1)
                ];
            }
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] =$lng['error_user_not_exist'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}