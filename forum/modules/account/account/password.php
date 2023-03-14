<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    $lng = array_merge($lng, core::load_lng('pass'));
    $page_title = $lng['change_password'];
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/account/', 'Tài khoản');
    $breadcrumb->add($lng['change_password']);
    $_breadcrumb = $breadcrumb->out();

    $error = array();
    if (IS_POST && TOKEN_VALID) {
        // change your password
        $oldpass = isset($_POST['oldpass']) ? trim($_POST['oldpass']) : '';
        $newpass = isset($_POST['newpass']) ? trim($_POST['newpass']) : '';
        $newconf = isset($_POST['newconf']) ? trim($_POST['newconf']) : '';
        if (!$oldpass || !$newpass || !$newconf) {
            $error[] = $lng['error_fields'];
        }
        if (!$error && md5(md5($oldpass)) !== $datauser['password']) {
            $error[] = $lng['error_old_password'];
        }
        if ($newpass != $newconf) {
            $error[] = $lng['error_new_password'];
        }

        if (!$error && (mb_strlen($newpass) < 6 || mb_strlen($newpass) > 32)) {
            $error[] = $lng['error_lenght'];
        }
        if (!$error) {
            // Write to the database
            mysql_query('UPDATE `users` SET `password` = "' . mysql_real_escape_string(md5(md5($newpass))) . '" WHERE `id` = "' . $user_id . '";');
            // observe and record COOKIES
            $_SESSION['ups'] = md5(md5($newpass));
            if (isset($_COOKIE['cuid']) && isset($_COOKIE['cups'])) {
                setcookie('cups', md5(md5($newpass)), SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
            }
            $tpl_file = 'page.success';
            $tpl_data['page_content'] = $lng['password_changed'];
        }
    }
    if (!$tpl_file) {
        $tpl_file = 'account::password';
        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
        $tpl_data['form_action'] = 'password';
    }
}