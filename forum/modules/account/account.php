<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($user_id) {

    $page_title = 'Tài khoản';
    require(ROOTPATH . 'system/header.php');

    $breadcrumb = new breadcrumb();
    $breadcrumb->add('Tài khoản');
    $_breadcrumb = $breadcrumb->out();

    if (IS_POST && TOKEN_VALID) {
        // accept data from the form, check and write to the database
        $error = array ();
        $sql = '';
        $datauser['imname'] = isset($_POST['imname']) ? functions::checkin(mb_substr($_POST['imname'], 0, 50)) : '';
        $datauser['live'] = isset($_POST['live']) ? functions::checkin(mb_substr($_POST['live'], 0, 100)) : '';
        $datauser['dayb'] = isset($_POST['dayb']) ? intval($_POST['dayb']) : 0;
        $datauser['monthb'] = isset($_POST['monthb']) ? intval($_POST['monthb']) : 0;
        $datauser['yearb'] = isset($_POST['yearb']) ? intval($_POST['yearb']) : 0;
        $datauser['about'] = isset($_POST['about']) ? functions::checkin(mb_substr($_POST['about'], 0, 500)) : '';
        $datauser['status'] = isset($_POST['status']) ? functions::checkin(mb_substr($_POST['status'], 0, 50)) : '';
        $datauser['mail'] = isset($_POST['mail']) ? functions::checkin(mb_substr($_POST['mail'], 0, 40)) : '';
        $datauser['mailvis'] = isset($_POST['mailvis']) ? 1 : 0;
        $datauser['facebook'] = isset($_POST['facebook']) ? functions::checkin(mb_substr($_POST['facebook'], 0, 40)) : '';
        $datauser['sex'] = isset($_POST['sex']) ? trim($_POST['sex']) : '';
        // password for email change
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        // carry out the necessary checks
        if ($datauser['imname'] && preg_match('/[^a-z\s]/', functions::unSign($datauser['imname']))) {
            $error[] = 'Định dạng tên thật không hợp lệ';
        }
        if ($datauser['dayb'] || $datauser['monthb'] || $datauser['yearb']) {
            if (!checkdate($datauser['monthb'], $datauser['dayb'], $datauser['yearb'])) {
                $error[] = $lng['error_birth'];
            }
        }
        if ($datauser['sex'] != 'm' && $datauser['sex'] != 'f') {
            $error[] = 'Giới tính không hợp lệ';
        }
        if (filter_var($datauser['mail'], FILTER_VALIDATE_EMAIL) === false) {
            $error = 'Định dạng email không hợp lệ!';
        }
        if (!$error) {
            if (mb_strlen($password) > 5 && mb_strlen($password) < 32 && md5(md5($password)) == $datauser['password']) {
                $sql .= ', `mail` = "' . mysql_real_escape_string($datauser['mail']) . '"';
            }
            mysql_query("UPDATE `users` SET
                `live` = '" . mysql_real_escape_string($datauser['live']) . "',
                `imname`   = '" . mysql_real_escape_string($datauser['imname']) . "',
                `dayb` = '" . $datauser['dayb'] . "',
                `monthb` = '" . $datauser['monthb'] . "',
                `yearb` = '" . $datauser['yearb'] . "',
                `about` = '" . mysql_real_escape_string($datauser['about']) . "',
                `status` = '" . mysql_real_escape_string($datauser['status']) . "',
                `mailvis` = '" . $datauser['mailvis'] . "',
                `facebook` = '" . mysql_real_escape_string($datauser['facebook']) . "',
                `sex` = '" . $datauser['sex'] . "'
                " . $sql . "
                WHERE `id` = '" . $user_id . "'
            ");
            header('Location: ' . SITE_URL . '/account/'); exit;
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($error);
            $tpl_data['back_url'] = SITE_URL . '/account/';
            $tpl_data['back_text'] = $lng['back'];
        }
    } else {
        // Form editing user profiles
        $tpl_file = 'account::account';
        $tpl_data['form_action'] = SITE_URL . '/account/';
        $tpl_data['user_account'] = $datauser['account'];
        $tpl_data['user_status'] = functions::checkout($datauser['status']);
        $tpl_data['user_avatar'] = functions::get_avatar($user_id);
        $tpl_data['edit_avatar_url'] = 'avatar';
        $tpl_data['user_name'] = $datauser['imname'];
        $tpl_data['user_sex'] = $datauser['sex'];
        $tpl_data['user_dayb'] = $datauser['dayb'];
        $tpl_data['user_monthb'] = $datauser['monthb'];
        $tpl_data['user_yearb'] = $datauser['yearb'];
        $tpl_data['user_live'] = functions::checkout($datauser['live']);
        $tpl_data['user_about'] = functions::checkout($datauser['about']);
        $tpl_data['user_mobile'] = (empty($datauser['mobile']) ? '' : ('0' . $datauser['mobile']));
        $tpl_data['user_mail'] = functions::checkout($datauser['mail']);
        $tpl_data['user_mailvis'] = ($datauser['mailvis'] ? ' checked="checked"' : '');
        $tpl_data['user_facebook'] = functions::checkout($datauser['facebook']);
    }
}
