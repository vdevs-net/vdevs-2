<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

require('system/header.php');
$breadcrumb = new breadcrumb();
$breadcrumb->add('Login with Facebook');
$_breadcrumb = $breadcrumb->out();

$fb_config = array(
    'app_id' => FB_APP_ID, // fb app ID
    'app_secret' => FB_APP_SECRET, // fb app secret
    'default_graph_version' => 'v2.8'
);
$fb = new Facebook\Facebook($fb_config);
$permissions = array(
    'scope' => 'email'
);
$helper = $fb->getRedirectLoginHelper();
if (isset($_SESSION['accessToken'])) {
    $accessToken = unserialize($_SESSION['accessToken']);
    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,email,gender,name,cover,picture.width(240).height(240).as(picture),verified', $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        $error = 'Graph returned an error: ' . $e->getMessage();
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        $error = 'Facebook SDK returned an error: ' . $e->getMessage();
    }
    if (isset($response)) {
        // Get User Data
        $data = $response->getGraphUser();
        if (
            !empty($data['id']) && !empty($data['email']) && !empty($data['gender']) && !empty($data['name'])
            && !empty($data['verified']) && $data['verified'] === true
        ) {
            $req = mysql_query('SELECT `id`, `password` FROM `users` WHERE `fb_id` = "' . $data['id'] . '" LIMIT 1');
            if (mysql_num_rows($req)) {
                $user = mysql_fetch_assoc($req);
                $_SESSION['uid'] = $user['id'];
                $_SESSION['ups'] = $user['password'];
                setcookie('cuid', base64_encode($user['id']), SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                setcookie('cups', $user['password'], SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                $next = $_SESSION['ref'];
                unset($_SESSION['ref']);
                unset($_SESSION['accessToken']);
                header('Location: '. $next .''); exit;
            } else {
                $error = array();
                $reg_account = isset($_POST['account']) ? functions::checkin($_POST['account']) : '';
                $reg_password = isset($_POST['password']) ? trim($_POST['password']) : '';
                $reg_cf_password = isset($_POST['cf_password']) ? functions::checkin($_POST['cf_password']) : '';
                if (IS_POST) {
                    if (mb_strlen($reg_account) < 5 || mb_strlen($reg_account) > 30) {
                        $error[] = 'Tên tài khoản phải từ 5 đến 30 ký tự!';
                    } elseif (preg_match('/[^\da-z.]|^[\d\.]|\.$|\.\.+/i', $reg_account)) {
                        $error[] = 'Tên tài khoản chỉ được sử dụng chữ cái, chữ số và dấu chấm ( . )';
                    } elseif (preg_match('/[^a-z\s]/', functions::unSign($data['name']))) {
                        $error[] = 'Họ và tên của tài khoản Facebook không hợp lệ!';
                    }
                    // check password
                    if (mb_strlen($reg_password) < 6 || mb_strlen($reg_password) > 32) {
                        $error[] = 'Mật khẩu phải từ 6 đến 32 ký tự!';
                    } elseif ($reg_password != $reg_cf_password) {
                        $error[] = 'Mật khẩu nhập lại không đúng!';
                    }
                    if (!$error) {
                        $check = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE REPLACE(`account`, ".", "") = "' . str_replace('.', '', $reg_account) . '"'), 0);
                        if ($check) {
                            $error[] = 'Tên tài khoản này đã được sử dụng';
                        }
                    }
                    if (!$error) {
                        $reg_sex = ($data['gender'] == 'male' ? 'm' : 'f');
                        $user_pass = md5(md5($reg_password));

                        mysql_query('INSERT INTO `users` SET
                            `account` = "' . mysql_real_escape_string($reg_account) . '",
                            `password` = "'. mysql_real_escape_string($user_pass) .'",
                            `fb_id` = "'. $data['id'] .'",
                            `imname` = "' . $data['name'] .'",
                            `about` = "",
                            `mail` = "' . mysql_real_escape_string($data['email']) . '",
                            `sex` = "' . $reg_sex . '",
                            `ip` = "' . core::$ip . '",
                            `ip_via_proxy` = "' . core::$ip_via_proxy . '",
                            `browser` = "' . mysql_real_escape_string($agn) . '",
                            `datereg` = "' . SYSTEM_TIME . '",
                            `lastdate` = "' . SYSTEM_TIME . '",
                            `sestime` = "' . SYSTEM_TIME . '",
                            `preg` = "1",
                            `set_user` = "",
                            `set_site` = ""
                        ') or exit(__LINE__ . ': ' . mysql_error());
                        $suid = mysql_insert_id();
                        if ($data['picture']['is_silhouette'] === false) {
                            $url = $data['picture']['url'];
                            if (($source = @file_get_contents($url)) != false) {
                                $path = explode('?', basename($url));
                                $filename = $path[0];
                                $_tmp = ROOTPATH . 'files/system/_tmp/' . $filename;
                                if (@file_put_contents($_tmp, $source) != false) {
                                    $handle = new upload($_tmp);
                                    if ($handle->uploaded) {
                                        $handle->allowed = array(
                                            'image/jpeg',
                                            'image/png'
                                        );
                                        // Big size
                                        $handle->file_new_name_body = $suid;
                                        $handle->file_overwrite = true;
                                        $handle->image_convert = 'png';
                                        $handle->process(ROOTPATH . 'files/users/avatar/');
                                        if ($handle->processed) {
                                            // Small size
                                            $handle->file_new_name_body = $suid . '_small';
                                            $handle->file_overwrite = true;
                                            $handle->image_resize = true;
                                            $handle->image_ratio_crop = true;
                                            $handle->image_x = 48;
                                            $handle->image_y = 48;
                                            $handle->image_convert = 'png';
                                            $handle->process(ROOTPATH . 'files/users/avatar/');
                                        }
                                        $handle->clean();
                                    }
                                }
                            }
                        }
                        $_SESSION['uid'] = $suid;
                        $_SESSION['ups'] = $user_pass;
                        setcookie('cuid', base64_encode($suid), SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                        setcookie('cups', $user_pass, SYSTEM_TIME + 3600 * 24 * 365, COOKIE_PATH);
                        $next = $_SESSION['ref'];
                        unset($_SESSION['ref']);
                        unset($_SESSION['accessToken']);
                        header('Location: '. $next .''); exit;
                    }
                }
                $tpl_file = 'login::facebook';
                $tpl_data['form_action'] = 'facebook';
                $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                $tpl_data['input_username'] = functions::checkout($reg_account);
                $tpl_data['fb_avatar_src']  = htmlspecialchars($data['picture']['url']);
                $tpl_data['fb_name']        = htmlspecialchars($data['name']);
                $tpl_data['fb_email']       = htmlspecialchars($data['email']);
            }
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = 'Tài khoản Facebook của bạn chưa cập nhật địa chỉ Email hoặc chưa được xác thực!';
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $error;
    }
} else {
    if(isset($_GET['code']) && isset($_GET['state'])){
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $error = 'Graph returned an error: ' . $e->getMessage();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $error = 'Facebook SDK returned an error: ' . $e->getMessage();
        }
        if(isset($accessToken)){
            $_SESSION['accessToken'] = serialize($accessToken);
            header('Location: facebook'); exit;
        } else {
            $tpl_file = 'page.error';
            $tpl_data['page_content'] = $error;
        }
    } else {
        $loginUrl = $helper->getLoginUrl(SITE_URL . '/login/facebook', $permissions);
        header('Location: ' . $loginUrl); exit;
    }
}