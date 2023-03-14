<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $page_title = $lng['upload_avatar'];
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/account/', 'Tài khoản');
    $breadcrumb->add($lng['upload_avatar']);
    $_breadcrumb = $breadcrumb->out();

    if (IS_POST && TOKEN_VALID) {
        if (isset($_POST['delete'])) {
            if (file_exists(ROOTPATH . 'files/users/avatar/' . $user_id . '_small.png')) {
                unlink(ROOTPATH . 'files/users/avatar/' . $user_id . '_small.png');
                unlink(ROOTPATH . 'files/users/avatar/' . $user_id . '.png');
            }
            header('Location: ' . SITE_URL . '/account/'); exit;
        } else {
            $error = false;
            $handle = new upload($_FILES['imagefile']);
            if ($handle->uploaded) {
                $handle->allowed = array(
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                );
                if ($handle->image_src_x > 240 && $handle->image_src_y > 240) {
                    $handle->file_max_size = 1024 * $set['flsz'];
                    $handle->file_new_name_body = $user_id;
                    $handle->file_overwrite = true;
                    $handle->image_resize = true;
                    $handle->image_ratio_crop = true;
                    $handle->image_x = 240;
                    $handle->image_y = 240;
                    $handle->image_convert = 'png';
                    $handle->process(ROOTPATH . 'files/users/avatar/');
                    if ($handle->processed) {
                        // small size
                        $handle->file_new_name_body = $user_id . '_small';
                        $handle->file_overwrite = true;
                        $handle->image_resize = true;
                        $handle->image_ratio_crop = true;
                        $handle->image_x = 48;
                        $handle->image_y = 48;
                        $handle->image_convert = 'png';
                        $handle->process(ROOTPATH . 'files/users/avatar/');
                        if ($handle->processed) {
                            header('Location: ' . SITE_URL . '/account/'); exit;
                        } else {
                            $error = $handle->error;
                        }
                    } else {
                        $error = $handle->error;
                    }
                } else {
                    $error = 'Kích thước ảnh đại diện tối thiểu là 240x240 px';
                }
                $handle->clean();
            } else {
                $error = 'Bạn chưa chọn hình ảnh nào!';
            }
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($error);
            $tpl_data['back_url'] = 'avatar';
            $tpl_data['back_text'] = $lng['repeat']; 
        }
    } else {
        $tpl_file = 'account::images';
        $tpl_data['form_action'] = 'avatar';
        $tpl_data['form_help'] = $lng['select_image_help'] . ' ' . $set['flsz'] . ' Kb.<br />' . $lng['select_image_help_3'];
        $tpl_data['lang_delete'] = 'Xóa ảnh đại diện?';
    }
}