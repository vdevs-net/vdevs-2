<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add($lng['login']);
$_breadcrumb = $breadcrumb->out();

$error = array();
$captcha = FALSE;
$show_captcha = FALSE;
$user_login = isset($_POST['account']) ? functions::checkin($_POST['account']) : NULL;
$user_pass = isset($_POST['password']) ? trim($_POST['password']) : NULL;

$tpl_data['input_account'] = functions::checkout($user_login);
if (IS_POST) {
    if (mb_strlen($user_login) < 3 || mb_strlen($user_login) > 30) {
        $error[] = 'Tên tài khoản không hợp lệ';
    }
    if (mb_strlen($user_pass) < 6 || mb_strlen($user_pass) > 32) {
        $error[] = 'Mật khẩu không hợp lệ';
    }
    if (!$error) {
        // Check Database
        $req = mysql_query('SELECT * FROM `users` WHERE REPLACE(`account`, ".", "") = "' . mysql_real_escape_string(str_replace('.', '', $user_login)) . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $user = mysql_fetch_assoc($req);
            if ($user['failed_login'] > 2) {
                if (isset($_POST['code'])) {
                    if (isset($_SESSION['code']) && mb_strlen($_POST['code']) > 3 && $_POST['code'] == $_SESSION['code']) {
                        // if captcha is match
                        unset($_SESSION['code']);
                        $captcha = TRUE;
                    } else {
                         // if not
                        unset($_SESSION['code']);
                        $error[] = $lng['error_wrong_captcha'];
                        $show_captcha = TRUE;
                    }
                } else {
                    // Show CAPTCHA
                    $show_captcha = TRUE;
                    $error[] = 'Tài khoản của bạn đã đăng nhập sai quá nhiều lần. Vui lòng nhập mã bảo vệ!';
                }
            }
            if ($user['failed_login'] < 3 || $captcha) {
                if (md5(md5($user_pass)) == $user['password']) {
                    // If a successful login
                    if ($user['preg']) {
                        // If all checks are successful, we prepare the entrance to the site
                        if (isset($_POST['mem'])) {
                            // setting COOKIE
                            $cuid = base64_encode($user['id']);
                            $cups = md5(md5($user_pass));
                            setcookie('cuid', $cuid, SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                            setcookie('cups', $cups, SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                        }
                        // 	Setting the session data
                        $_SESSION['uid'] = $user['id'];
                        $_SESSION['ups'] = md5(md5($user_pass));
                        mysql_query('UPDATE `users` SET `failed_login` = "0", `sestime` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user['id'] . '"');
                        $next = $_SESSION['ref'];
                        unset($_SESSION['ref']);
                        header('Location: ' . $next); exit;
                    } else {
                        // If the registration is not confirmed
                        mysql_query('UPDATE `users` SET `failed_login` = "0" WHERE `id` = "' . $user['id'] . '"');
                        $error[] = 'Tài khoản của bạn chưa được kích hoạt!';
                    }
                } else {
                    // If the login failed
                    if ($user['failed_login'] < 3) {
                        // Added to the counter of failed logins
                        mysql_query('UPDATE `users` SET `failed_login` = "' . ($user['failed_login'] + 1) . '" WHERE `id` = "' . $user['id'] . '"');
                    }
                    if($user['failed_login'] >= 2) {
                        $show_captcha = TRUE;
                    }
                    $error[] = $lng['authorisation_not_passed'];
                }
            }
        } else {
            $error[] = $lng['authorisation_not_passed'];
        }
    }
}

$tpl_file = 'login::login';
$tpl_data['alert'] = ($set['site_access'] == 0 ? $lng['info_only_sv'] : ($set['site_access'] == 1 ? $lng['info_only_adm'] : ''));
$tpl_data['error'] = ($error ? functions::display_error($error) : '');
$tpl_data['form_action'] = SITE_URL . '/login/';
$tpl_data['show_captcha'] = $show_captcha;
$tpl_data['captcha_url'] = SITE_URL . '/assets/captcha.php?r=' . rand(1000, 9999);
