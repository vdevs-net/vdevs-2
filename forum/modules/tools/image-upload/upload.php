<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Upload ảnh';

$breadcrumb = new breadcrumb();
$breadcrumb->add('/tools/', 'Công cụ');
$breadcrumb->add('/tools/image-upload/', 'Upload ảnh');
$breadcrumb->add('Upload ảnh mới');
$_breadcrumb = $breadcrumb->out();

if ($user_id) {
    if (empty($ban)) {
        $error = '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $token = isset($_POST['token']) ? trim($_POST['token']) : '';
        if (IS_POST && isset($_SESSION['token']) && mb_strlen($token) > 4 && $token == $_SESSION['token']) {
            if ($type == 'url') {
                $url = isset($_POST['url']) ? trim($_POST['url']) : '';
                if (empty($url)) {
                    $error = 'Please enter a URL!';
                } else {
                    $url = filter_var($url, FILTER_SANITIZE_URL);
                    if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false) {
                        $imgur = new imgur();
                        $imgur->upload($url, 'url');
                        if ($imgur->error) {
                            $error = $imgur->error;
                        } else {
                            mysql_query('INSERT INTO `cms_images` SET `user_id` = "' . $user_id . '", `time` = "' . SYSTEM_TIME . '", `size` = "' . $imgur->data['size'] . '", `width` = "' . $imgur->data['width'] . '", `height` = "' . $imgur->data['height'] . '", `link` = "' . $imgur->data['link'] . '", `deleteHash` = "' . $imgur->data['deletehash'] . '"');
                            header('Location: details?id=' . mysql_insert_id()); exit;
                        }
                    } else {
                        $error = 'Invalid URL';
                    }
                }
            } else {
                reset($_FILES);
                $file = current($_FILES);
                if(is_uploaded_file($file['tmp_name'])) {
                    $imgur = new imgur();
                    $imgur->upload($file, 'file');
                    if ($imgur->error) {
                        $error = $imgur->error;
                    } else {
                        mysql_query('INSERT INTO `cms_images` SET `user_id` = "' . $user_id . '", `time` = "' . SYSTEM_TIME . '", `size` = "' . $imgur->data['size'] . '", `width` = "' . $imgur->data['width'] . '", `height` = "' . $imgur->data['height'] . '", `link` = "' . $imgur->data['link'] . '", `deleteHash` = "' . $imgur->data['deletehash'] . '"');
                        header('Location: details?id=' . mysql_insert_id()); exit;
                    }
                } else {
                    $error = 'Please choose an image!';
                }
            }
            unset($_SESSION['token']);
        }
        $token = mt_rand(10000, 99999);
        $_SESSION['token'] = $token;
        $tpl_file = 'tools::image-upload.upload';
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['form_action'] = 'upload' . ($type == 'url' ? '?type=url' : '');
        $tpl_data['type'] = $type;
        $tpl_data['token'] = $token;
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['access_guest_forbidden'];
}