<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$lng = array_merge($lng, core::load_lng('pass'));
$page_title = $lng['password_restore'];

$breadcrumb = new breadcrumb();
$breadcrumb->add('/account/', 'Tài khoản');
$breadcrumb->add($lng['password_restore']);
$_breadcrumb = $breadcrumb->out();

switch ($act) {
    case 'set':
        /*
        -----------------------------------------------------------------
        Устанавливаем новый пароль
        -----------------------------------------------------------------
        */
        $code = isset($_GET['code']) ? trim($_GET['code']) : '';
        $error = false;
        if (!$id || !$code) {
            $error = $lng['error_wrong_data'];
        }
        if (!$error) {
            $req = mysql_query('SELECT `account`, `mail`, `rest_code`, `rest_time` FROM `users` WHERE `id` = "' . $id . '" LIMIT 1');
            if (mysql_num_rows($req)) {
                $res = mysql_fetch_assoc($req);
                if (empty($res['rest_code'])) {
                    $error = $lng['error_fatal'];
                } elseif ($res['rest_time'] < SYSTEM_TIME - 86400 || $code != $res['rest_code']) {
                    $error = $lng['error_timelimit'];
                }
            } else {
                $error = $lng['error_user_not_exist'];
            }
        }
        if (!$error) {
            // Высылаем пароль на E-mail
            $pass = functions::rand_code(9);
            $subject = $lng['your_new_password'];
            $mail = $lng['restore_help1'] . ', ' . $res['account'] . '<br />' . $lng['restore_help8'] . ' ' . SITE_URL . '<br />';
            $mail .= $lng['your_new_password'] . ': ' . $pass . '<br />';
            $mail .= $lng['restore_help7'];
            $send = functions::mail($subject, $mail, $res['mail'], $res['account']);
            if ($send === true) {
                mysql_query('UPDATE `users` SET `rest_code` = "", `password` = "' . md5(md5($pass)) . '" WHERE `id` = "' . $id . '"');
                $tpl_file = 'page.success';
                $tpl_data['page_content'] = $lng['change_password_conf'];
            } else {
                $error = $lng['error_email_sent'];
            }
        }
        if (!$tpl_file) {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = functions::display_error($error);
        }
        break;

    default:
        $error = false;
        if (IS_POST) {
            $nick = isset($_POST['nick']) ? functions::checkin($_POST['nick']) : '';
            $email = isset($_POST['email']) ? functions::checkin($_POST['email']) : '';
            $code = isset($_POST['code']) ? trim($_POST['code']) : '';
            $check_code = md5(functions::rand_code(9));
            $error = false;
            if (!$nick || !$email) {
                $error = $lng['error_empty_fields'];
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $error = 'Định dạng email không hợp lệ!';
            } elseif (!isset($_SESSION['code']) || mb_strlen($code) < 4 || $code != $_SESSION['code']) {
                $error = $lng['error_code'];
            }
            unset($_SESSION['code']);
            if (!$error) {
                // Проверяем данные по базе
                $req = mysql_query('SELECT `id`, `account`, `mail`, `rest_time` FROM `users` WHERE `account` = "' . mysql_real_escape_string($nick) . '" LIMIT 1');
                if (mysql_num_rows($req) == 1) {
                    $res = mysql_fetch_array($req);
                    if (empty($res['mail']) || $res['mail'] != $email) {
                        $error = $lng['error_email'];
                    } elseif ($res['rest_time'] > SYSTEM_TIME - 86400) {
                        $error = $lng['restore_timelimit'];
                    }
                } else {
                    $error = $lng['error_user_not_exist'];
                }
            }
            if (!$error) {
                // Высылаем инструкции на E-mail
                $subject = $lng['password_restore'];
                $mail = $lng['restore_help1'] . ', ' . $res['account'] . '<br />' . $lng['restore_help2'] . ' ' . SITE_URL . '<br />';
                $mail .= $lng['restore_help3'] . ': <br />' . SITE_URL . "/account/recover?act=set&id=" . $res['id'] . "&code=" . $check_code . '<br /><br />';
                $mail .= $lng['restore_help4'] . '<br />';
                $mail .= $lng['restore_help5'];
                $send = functions::mail($subject, $mail, $res['mail'], $res['account']);
                if ($send === true) {
                    mysql_query('UPDATE `users` SET `rest_code` = "' . $check_code . '", `rest_time` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $res['id'] . '"');
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['restore_help6'];
                } else {
                    $error = $lng['error_email_sent'];
                }
            }
        } 
        if (!$tpl_file) {
            $tpl_file = 'account::recover';
            $tpl_data['error'] = ($error ? functions::display_error($error) : '');
            $tpl_data['form_action'] = 'recover';
            $tpl_data['captcha_src'] = SITE_URL . '/assets/captcha.php?r=' . rand(1000, 9999);
        }
}