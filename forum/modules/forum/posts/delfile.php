<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights == 9) {
        // Check whether the user fills in the file and whether to place
        $post_req = mysql_query('SELECT `refid` FROM `phonho_posts` WHERE `id` = "'. $id .'" LIMIT 1');
        if (mysql_num_rows($post_req)) {
            $freq = mysql_query('SELECT `filename` FROM `cms_forum_files` WHERE `post` = "' . $id . '" LIMIT 1');
            if (mysql_num_rows($freq) > 0) {
                $fres = mysql_fetch_assoc($freq);
                if (IS_POST && TOKEN_VALID) {
                    unlink(ROOTPATH . 'files/forum/attach/' . $fres['filename']);
                    mysql_query('DELETE FROM `cms_forum_files` WHERE `post` = "' . $id . '" LIMIT 1');
                    header('Location: ' . SITE_URL . '/forum/posts/' . $id . '/'); exit;
                } else {
                    require(ROOTPATH . 'system/header.php');
                    get_breadcrumb(0, ['Xóa đính kèm'], $_breadcrumb);
                    $tpl_file = 'page.confirm';
                    $tpl_data['form_action'] = SITE_URL . '/forum/posts/' . $id . '/delfile';
                    $tpl_data['confirm_text'] = 'Bạn có chắc chắn muốn xóa tập tin này?';
                    $tpl_data['cancel_url'] = SITE_URL . '/forum/posts/' . $id . '/';
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_file_not_exist'];
                $tpl_data['back_url'] = SITE_URL . '/forum/posts/' . $id . '/';
                $tpl_data['back_text'] = $lng['back'];
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $lng['error_post_deleted'];
        }
    } else {
        $error_rights = true;
    }
}