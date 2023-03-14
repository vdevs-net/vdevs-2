<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {
    header('Location: ' . SITE_URL); exit;
}

$page_title = $lng['registration'];
$headmod = 'registration';
$lng = array_merge($lng, core::load_lng('registration'));

// If the registration is closed, a warning is displayed
if (core::$deny_registration || !$set['mod_reg']) {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['registration_closed'];
} else {
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add($lng['registration']);
    $_breadcrumb = $breadcrumb->out();

    $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : NULL;
    $account = isset($_POST['account']) ? functions::checkin($_POST['account']) : '';
    $reg_pass = isset($_POST['password']) ? trim($_POST['password']) : '';
    $reg_cf_pass = isset($_POST['cf_password']) ? trim($_POST['cf_password']) : '';
    $reg_name = isset($_POST['imname']) ? functions::checkin(mb_substr($_POST['imname'], 0, 30)) : '';
    $reg_about = isset($_POST['about']) ? functions::checkin(mb_substr($_POST['about'], 0, 500)) : '';
    $reg_sex = isset($_POST['sex']) ? functions::checkin(mb_substr(trim($_POST['sex']), 0, 1)) : '';


    $tpl_data['input_account'] = functions::checkout($account);
    $tpl_data['input_password'] = functions::checkout($reg_pass);

    $error = array();
    if (IS_POST) {
        // Check account
        if (mb_strlen($account) < 5 || mb_strlen($account) > 30) {
            $error['login'] = 'Tên tài khoản phải từ 5 đến 30 ký tự!';
        } elseif (preg_match('/[^\da-z.]|^[\d\.]|\.$|\.\.+/i', $account)) {
            $error['login'] = 'Tên tài khoản chứa ký tự không hợp lệ';
        }
        // check password
        if (mb_strlen($reg_pass) < 6 || mb_strlen($reg_pass) > 32) {
            $error['password'] = 'Mật khẩu phải từ 6 đến 32 ký tự';
        }
        if ($reg_cf_pass != $reg_pass) {
            $error['cf_password'] = 'Mật khẩu nhập lại không đúng!';
        }
        // check full name
        if (mb_strlen($reg_name) < 3 || mb_strlen($reg_name) > 32) {
            $error['name'] = 'Họ và tên phải từ 3 đến 32 ký tự';
        } elseif (preg_match('/[^a-z\s]/', functions::unSign($reg_name))) {
            $error['name'] = 'Họ và tên chứa ký tự không hợp lệ';
        }
        // Check gender
        if ($reg_sex != 'm' && $reg_sex != 'f') {
            $error['sex'] = $lng['error_sex'];
        }
        // Check CAPTCHA
        if (!$captcha
            || !isset($_SESSION['code'])
            || mb_strlen($captcha) < 4
            || $captcha != $_SESSION['code']
        ) {
            $error['captcha'] = $lng['error_wrong_captcha'];
        }
        unset($_SESSION['code']);

        // Checking variables
        if (empty($error)) {
            // Check nick name is used?
            if (mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE REPLACE(`account`, ".", "") = "' . mysql_real_escape_string(str_replace('.', '', $account)) . '"'), 0)) {
                $error['login'] = $lng['error_nick_occupied'];
            }
        }
        if (empty($error)) {
            $tpl_file = 'account::register.success';
            mysql_query('INSERT INTO `users` SET
                `account` = "' . mysql_real_escape_string($account) . '",
                `password` = "' . mysql_real_escape_string(md5(md5($reg_pass))) . '",
                `imname` = "' . $reg_name . '",
                `about` = "' . mysql_real_escape_string($reg_about) . '",
                `sex` = "' . $reg_sex . '",
                `rights` = "0",
                `ip` = "' . core::$ip . '",
                `ip_via_proxy` = "' . core::$ip_via_proxy . '",
                `browser` = "' . mysql_real_escape_string($agn) . '",
                `datereg` = "' . SYSTEM_TIME . '",
                `lastdate` = "' . SYSTEM_TIME . '",
                `sestime` = "' . SYSTEM_TIME . '",
                `preg` = "' . ($set['mod_reg'] > 1 ? 1 : 0) . '",
                `set_user` = "",
                `set_site` = ""
            ') or exit(__LINE__ . ': ' . mysql_error());
            $usid = mysql_insert_id();
            $tpl_data['registered_id'] = $usid;
            $tpl_data['need_activate'] = ($set['mod_reg'] == 1);
            if ($set['mod_reg'] != 1) {
                $_SESSION['uid'] = $usid;
                $_SESSION['ups'] = md5(md5($reg_pass));
            }
        }
    }

    if (!$tpl_file) {
        $tpl_file = 'account::register';
        // registration form
        $tpl_data['alert'] = ($set['mod_reg'] == 1 ? $lng['moderation_warning'] : '');
        $tpl_data['error'] = ($error ? functions::display_error($error): '');
        $tpl_data['form_action'] = SITE_URL . '/account/register';
        $tpl_data['input_sex'] = $reg_sex;
        $tpl_data['input_name'] = functions::checkout($reg_name);
        $tpl_data['input_cf_password'] = functions::checkout($reg_cf_pass);
        $tpl_data['input_about'] = functions::checkout($reg_about);
        $tpl_data['error_account'] = isset($error['login']);
        $tpl_data['error_password'] = isset($error['password']);
        $tpl_data['error_cf_password'] = isset($error['cf_password']);
        $tpl_data['error_sex'] = isset($error['sex']);
        $tpl_data['error_name'] = isset($error['name']);
        $tpl_data['error_captcha'] = isset($error['captcha']);
        $tpl_data['captcha_src'] = SITE_URL . '/assets/captcha.php?r=' . rand(1000, 9999);
    }
}