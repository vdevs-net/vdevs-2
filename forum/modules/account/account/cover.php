<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $page_title = $lng['upload_photo'];
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/account/', 'Tài khoản');
    $breadcrumb->add($lng['upload_photo']);
    $_breadcrumb = $breadcrumb->out();

    if (IS_POST && TOKEN_VALID) {
        if (isset($_POST['delete'])) {
            if (file_exists(ROOTPATH . 'files/users/cover/' . $user_id . '.jpg')) {
                unlink(ROOTPATH . 'files/users/cover/' . $user_id . '.jpg');
                unlink(ROOTPATH . 'files/users/cover/' . $user_id . '_small.jpg');
            }
            header('Location: ' . SITE_URL . '/profile/' . $datauser['account'] . '.' . $user_id . '/'); exit;
        } else {
            $error = false;
            $handle = new upload($_FILES['imagefile']);
            if ($handle->uploaded) {
                $handle->allowed = array(
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                );
                if ($handle->image_src_x > 640 && $handle->image_src_y > 300) {
                    $handle->file_max_size = 1024 * $set['flsz'];
                    $handle->file_new_name_body = $user_id;
                    $handle->file_overwrite = true;
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_x = 640;
                    $handle->image_convert = 'jpg';
                    $handle->process(ROOTPATH . 'files/users/cover/');
                    if ($handle->processed) {
                        // Create thumbnail
                        $handle->file_new_name_body = $user_id . '_small';
                        $handle->file_overwrite = true;
                        $handle->image_resize = true;
                        $handle->image_ratio_y = true;
                        $handle->image_x = 240;
                        $handle->image_convert = 'jpg';
                        $handle->process(ROOTPATH . 'files/users/cover/');
                        if ($handle->processed) {
                            header('Location: ' . SITE_URL . '/profile/' . $datauser['account'] . '.' . $user_id . '/'); exit;
                        } else {
                            $error = $handle->error;
                        }
                    } else {
                        $error = $handle->error;
                    }
                } else {
                    $error = 'Kích thước ảnh bìa tối thiểu là 640x300 px để có thể hiển thị tốt trên cả máy tính và điện thoại!';
                }
                $handle->clean();
            } else {
                $error = 'Bạn chưa chọn hình ảnh nào!';
            }
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($error);
            $tpl_data['back_url'] = 'cover';
            $tpl_data['back_text'] = $lng['repeat'];
        }
    } else {
        $tpl_file = 'account::images';
        $tpl_data['form_action'] = 'cover';
        $tpl_data['form_help'] = $lng['select_image_help'] . ' ' . $set['flsz'] . ' Kb.<br />' . $lng['select_image_help_3'];
        $tpl_data['lang_delete'] = 'Xóa ảnh bìa?';
    }
}