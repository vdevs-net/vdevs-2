<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Upload ảnh';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/tools/', 'Công cụ');
$breadcrumb->add('/tools/image-upload/', 'Upload ảnh');
$breadcrumb->add('Xóa ảnh');
$_breadcrumb = $breadcrumb->out();

if ($user_id) {
    if ($id) {
        if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_images` WHERE `id` = "' . $id . '"'), 0)) {
            $res = mysql_fetch_assoc(mysql_query('SELECT `cms_images`.*, `users`.`rights` FROM `cms_images` LEFT JOIN `users` ON `users`.`id` = `cms_images`.`user_id` WHERE `cms_images`.`id` = "' . $id . '" LIMIT 1'));
            if ($user_id == $res['user_id'] || ($rights >= 7 && $rights > $res['rights'])) {
                if (IS_POST && TOKEN_VALID) {
                        $imgur = new imgur();
                        $imgur->delete($res['deleteHash']);
                        if ($imgur->deleted) {
                            mysql_query('DELETE FROM `cms_images` WHERE `id` = "' . $id . '"');
                            header('Location: ' . SITE_URL . '/tools/image-upload/'); exit;
                        } else {
                            $error = 'Error when delete the image! Please try again late!';
                        }
                    } else {
                        $tpl_file = 'page.confirm';
                        $tpl_data['form_action'] = 'delete?id=' . $id;
                        $tpl_data['confirm_text'] = 'Are you sure?';
                        $tpl_data['cancel_url'] = 'details?id=' . $id;
                    }
                } else {
                    $error = $lng['access_forbidden'];
                }
            } else {
                $error = $lng['error_wrong_data'];
            }
        } else {
            $error = $lng['error_wrong_data'];
        }
        if (!$tpl_file) {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $error;
        }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['access_guest_forbidden'];
}