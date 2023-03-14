<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Upload Ảnh';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/tools/', 'Công cụ');
$breadcrumb->add('/tools/image-upload/', 'Upload ảnh');
$breadcrumb->add('Thông tin hình ảnh');
$_breadcrumb = $breadcrumb->out();

if ($id) {
    $req = mysql_query('SELECT `cms_images`.*, `users`.`account`, `users`.`rights` FROM `cms_images` LEFT JOIN `users` ON `users`.`id` = `cms_images`.`user_id` WHERE `cms_images`.`id` = "' . $id . '" LIMIT 1');
    if (mysql_num_rows($req)) {
        $tpl_file = 'tools::image-upload.details';
        $res = mysql_fetch_assoc($req);
        if ($res['width'] >= 320 || $res['height'] >= 320) {
            if ($res['width'] == $res['height']) {
                $thumb_width = $thumb_height = 320;
            } elseif ($res['width'] > $res['height']) {
                $thumb_width = 320;
                $thumb_height = ceil(320 * $res['height'] / $res['width']);
            } else {
                $thumb_height = 320;
                $thumb_width = ceil(320 * $res['width'] / $res['height']);
            }
        } else {
            $thumb_width = $res['width'];
            $thumb_height = $res['height'];
        }
        $tpl_data['thumb_src'] = preg_replace('|^https?:|', '', functions::imgurSize($res['link'], 'm'));
        $tpl_data['thumb_width'] = $thumb_width;
        $tpl_data['thumb_height'] = $thumb_height;
        $tpl_data['image_src'] = $res['link'];
        $tpl_data['uploader'] = $res['account'];
        $tpl_data['uploader_id'] = $res['user_id'];
        $tpl_data['uploader_html_class'] = 'user_' . $res['rights'];
        $tpl_data['uploader_profile_url'] = SITE_URL . '/profile/' . $res['account'] . '.' . $res['user_id'] . '/';
        $tpl_data['upload_time'] = functions::display_date($res['time']);
        $tpl_data['file_size'] = round($res['size'] / 1024, 2);
        $tpl_data['file_width'] = $res['width'];
        $tpl_data['file_height'] = $res['height'];
        $tpl_data['can_delete'] =  ($user_id == $res['user_id'] || ($rights >= 7 && $rights > $res['rights']));
        $tpl_data['delete_url'] = 'delete?id=' . $id;
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_wrong_data'];
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}