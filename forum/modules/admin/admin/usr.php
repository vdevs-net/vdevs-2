<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

if ($id) {
    $breadcrumb->add('/admin/usr', $lng['users']);
    $lng = array_merge($lng, core::load_lng('profile'));
    $thisUser = functions::get_user($id);
    if ($thisUser) {
        $breadcrumb->add($thisUser['account']);
        $menu = array();
        if ($thisUser['id'] != $user_id && $rights >= 7 && $rights > $thisUser['rights']) {
            $menu[] = '<a href="usr?mod=edit&id=' . $thisUser['id'] . '">' . $lng['edit'] . '</a>';
        }
        if ($thisUser['id'] != $user_id && $rights >= 7 && $rights > $thisUser['rights']) {
            $menu[] = '<a href="usr-del?id=' . $thisUser['id'] . '">' . $lng['delete'] . '</a>';
        }
        if ($thisUser['id'] != $user_id && $rights >= 7 && $rights > $thisUser['rights']) {
            $menu[] = '<a href="usr?mod=password&id=' . $thisUser['id'] . '">' . $lng['change_password'] . '</a>';
        }
        if ($thisUser['id'] != $user_id && $rights > $thisUser['rights']) {
            $menu[] = '<a href="usr?mod=ban&amp;id=' . $thisUser['id'] . '">' . $lng['ban_do'] . '</a>';
        }
        $tpl_data['userInfoVariable']['menu'] = $menu;
        $tpl_data['userInfoVariable']['userInfo'] = functions::display_user($thisUser, array(
                'lastvisit' => 1,
                'iphist'    => 1));
        switch ($mod) {
            case 'password':
                if ($rights >= 7 && $rights > $thisUser['rights']) {
                    $lng = array_merge($lng, core::load_lng('pass'));
                    $display_form = true;
                    $error = array();
                    $newpass = isset($_POST['newpass']) ? trim($_POST['newpass']) : '';
                    $newconf = isset($_POST['newconf']) ? trim($_POST['newconf']) : '';
                    if (IS_POST && TOKEN_VALID) {
                        if (!$newpass || !$newconf) {
                            $error[] = $lng['error_fields'];
                        }
                        if ($newpass != $newconf) {
                            $error[] = $lng['error_new_password'];
                        }

                        if (!$error && (strlen($newpass) < 6 || strlen($newpass) > 32)) {
                            $error[] = $lng['error_lenght'];
                        }
                        if (!$error) {
                            // Write to the database
                            mysql_query('UPDATE `users` SET `password` = "' . mysql_real_escape_string(md5(md5($newpass))) . '" WHERE `id` = "' . $thisUser['id'] . '";');
                            $tpl_file = 'page.success';
                            $tpl_data['page_content'] = $lng['password_changed'];
                            $display_form = false;
                        }
                    }
                    if ($display_form) {
                        $tpl_file = 'admin::usr.password';
                        $tpl_data['formAction'] = 'usr?mod=password&id=' . $thisUser['id'];
                        $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                    }
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['access_forbidden'];
                }
                break;

            case 'ban':
                $lng = array_merge($lng, core::load_lng('ban'));
                $_ban = isset($_GET['ban']) ? intval($_GET['ban']) : 0;
                switch ($do) {
                    case 'cancel':
                        // Разбаниваем пользователя (с сохранением истории)
                        $error = false;
                        if (!$_ban || $thisUser['id'] == $user_id || $rights < 7) {
                            $error = $lng['error_wrong_data'];
                        } else {
                            $req = mysql_query('SELECT * FROM `cms_ban_users` WHERE `id` = "' . $_ban . '" AND `user_id` = "' . $thisUser['id'] . '"');
                            if (mysql_num_rows($req)) {
                                $res = mysql_fetch_assoc($req);
                                if ($res['ban_time'] > time()) {
                                    $tpl_file = 'admin::usr.ban-cancel';
                                    $tpl_data['title'] = $lng['ban_cancel'];
                                    $tpl_data['success'] = false;
                                    $tpl_data['formAction'] = 'usr?mod=ban&do=cancel&id=' . $thisUser['id'] . '&ban=' . $_ban;
                                    if (IS_POST && TOKEN_VALID) {
                                        mysql_query('UPDATE `cms_ban_users` SET `ban_time`="' . time() . '" WHERE `id` = "' . $_ban . '"');
                                        $tpl_data['success'] = true;
                                        $tpl_data['successText'] = $lng['ban_cancel_confirmation'];
                                    } else {
                                        $tpl_data['formAlert'] = $lng['ban_cancel_help'];
                                        $tpl_data['submitText'] = $lng['ban_cancel_do'];
                                    }
                                } else {
                                    $error = $lng['error_ban_not_active'];
                                }
                            } else {
                                $error = $lng['error_wrong_data'];
                            }
                        }
                        if ($error) {
                            $tpl_file = 'page.error';
                            $tpl_data['page_content'] = functions::display_error($error);
                        }
                        break;

                    case 'delete':
                        // Удаляем бан (с удалением записи из истории)
                        $error = false;
                        if (!$_ban || $rights < 9) {
                            $error = $lng['error_wrong_data'];
                        } else {
                            $req = mysql_query('SELECT * FROM `cms_ban_users` WHERE `id` = "' . $_ban . '" AND `user_id` = "' . $thisUser['id'] . '"');
                            if (mysql_num_rows($req)) {
                                $res = mysql_fetch_assoc($req);
                                $tpl_file = 'admin::usr.ban-cancel';
                                $tpl_data['title'] = $lng['ban_delete'];
                                $tpl_data['success'] = false;
                                $tpl_data['formAction'] = 'usr?mod=ban&do=delete&id=' . $thisUser['id'] . '&amp;ban=' . $_ban;
                                if (IS_POST && TOKEN_VALID) {
                                    mysql_query('DELETE FROM `cms_ban_users` WHERE `id` = "' . $_ban . '"');
                                    $tpl_data['success'] = true;
                                    $tpl_data['successText'] = $lng['ban_deleted'];
                                } else {
                                    $tpl_data['formAlert'] = $lng['ban_delete_help'];
                                    $tpl_data['submitText'] = $lng['delete'];
                                }
                            } else {
                                $error = $lng['error_wrong_data'];
                            }
                        }
                        if ($error) {
                            $tpl_file = 'page.error';
                            $tpl_data['page_content'] = functions::display_error($error);
                        }
                        break;

                    case 'delhist':
                        // Очищаем историю нарушений юзера
                        if ($rights == 9) {
                            $tpl_file = 'admin::usr.ban-delhist';
                            $tpl_data['success'] = false;
                            $tpl_data['total'] = 0;
                            $tpl_data['formAction'] = '';
                            $tpl_data['banHistoryUrl'] = SITE_URL . '/profile/' . $thisUser['account'] . '.' . $thisUser['id'] . '/ban';
                            $tpl_data['banPanelUrl'] = SITE_URL . '/' . $set['admp'] . '/ban-panel';
                            if (IS_POST) {
                                mysql_query('DELETE FROM `cms_ban_users` WHERE `user_id` = "' . $thisUser['id'] . '"');
                                $tpl_data['success'] = true;
                            } else {
                                $tpl_data['formAction'] = 'usr?mod=ban&do=delhist&id=' . $thisUser['id'];
                                $tpl_data['total'] = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_ban_users` WHERE `user_id` = '" . $thisUser['id'] . "'"), 0);
                            }
                        } else {
                            $tpl_file = 'page.error';
                            $tpl_data['page_content'] = $lng['error_rights_clear'];
                        }
                        break;

                    default:
                        // Ban user (Ban add to the database)
                        // Pramete
                        //  1 - Blocked
                        //  3 - PM
                        // 10 - Comment
                        // 11 - Forum
                        // 12 - Chat
                        // 15 - Library
                        //
                        if ($rights < 1 || ($rights < 6 && $thisUser['rights']) || ($rights <= $thisUser['rights'])) {
                            $tpl_file = 'page.error';
                            $tpl_data['page_content'] = $lng['ban_rights'];
                        } else {
                            $tpl_file = 'admin::usr.ban';
                            $error = false;
                            $tpl_data['success'] = false;
                            if (IS_POST) {
                                $term = isset($_POST['term']) ? intval($_POST['term']) : 0;
                                $timeval = isset($_POST['timeval']) ? intval($_POST['timeval']) : 0;
                                $time = isset($_POST['time']) ? intval($_POST['time']) : 0;
                                $reason = !empty($_POST['reason']) ? functions::checkin($_POST['reason']) : '';
                                if (empty($reason)) {
                                    $reason = $lng['reason_not_specified'];
                                }
                                if (!$term || !$timeval || !$time) {
                                    $error = $lng['error_data'];
                                }
                                if (($rights == 2 && $term != 12) || ($rights == 3 && $term != 11) || ($rights == 5 && $term != 15)) {
                                    $error = $lng['error_rights_section'];
                                }
                                if (mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_ban_users` WHERE `user_id` = "' . $thisUser['id'] . '" AND `ban_time` > "' . time() . '" AND `ban_type` = "' . $term . '"'), 0)) {
                                    $error = $lng['error_ban_exist'];
                                }
                                switch ($time) {
                                    case 2:
                                        // hours
                                        if ($timeval > 24) {
                                            $timeval = 24;
                                        }
                                        $timeval = $timeval * 3600;
                                        break;

                                    case 3:
                                        // days
                                        if ($timeval > 30) {
                                            $timeval = 30;
                                        }
                                        $timeval = $timeval * 86400;
                                        break;

                                    case 4:
                                        // until unban (max 10 yeah)
                                        $timeval = 315360000;
                                        break;

                                    default:
                                        // minutes
                                        if ($timeval > 60) {
                                            $timeval = 60;
                                        }
                                        $timeval = $timeval * 60;
                                }
                                if ($datauser['rights'] < 6 && $timeval > 86400) {
                                    $timeval = 86400;
                                }
                                if ($datauser['rights'] < 7 && $timeval > 2592000) {
                                    $timeval = 2592000;
                                }
                                if (!$error) {
                                    // entered into the database
                                    mysql_query('INSERT INTO `cms_ban_users` SET
                                        `user_id` = "' . $thisUser['id'] . '",
                                        `ban_time` = "' . (time() + $timeval) . '",
                                        `ban_while` = "' . time() . '",
                                        `ban_type` = "' . $term . '",
                                        `ban_who` = "' . $login . '",
                                        `ban_reason` = "' . mysql_real_escape_string($reason) . '"
                                    ');
                                    $tpl_data['success'] = true;
                                }
                            }
                            $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                            $tpl_data['formAction'] = 'usr?mod=ban&id=' . $thisUser['id'];
                        }
                        break;
                }
                break;
            case 'ip':
                $tpl_file = 'admin::usr.ip';
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_users_iphistory` WHERE `user_id`="' . $thisUser['id'] . '"'), 0);
                $tpl_data['total'] = $total;
                $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('usr?mod=ip&id=' . $thisUser['id'] . '&page=', $start, $total, $kmess) : '');
                $tpl_data['items'] = [];
                if ($total) {
                    $req = mysql_query('SELECT * FROM `cms_users_iphistory` WHERE `user_id`="' . $thisUser['id'] . '" ORDER BY `time` DESC LIMIT ' . $start . ', ' . $kmess);
                    while ($res = mysql_fetch_assoc($req)) {
                        $tpl_data['items'][] = [
                            'url' => 'search-ip?mod=history&amp;ip=' . long2ip($res['ip']),
                            'ip' => long2ip($res['ip']),
                            'time' => functions::display_date($res['time'])
                        ];
                    }
                }
                break;

            default:
                $tpl_file = 'admin::usr.edit';
                $tpl_data['result'] = null;
                $error = array();
                if ($rights >= 7 && $rights > $thisUser['rights']) {
                    if (isset($_GET['delavatar']) && file_exists(ROOTPATH . 'files/users/avatar/' . $thisUser['id'] . '_small.png')) {
                        // remove avatar
                        unlink(ROOTPATH . 'files/users/avatar/' . $thisUser['id'] . '.png');
                        unlink(ROOTPATH . 'files/users/avatar/' . $thisUser['id'] . '_small.png');
                        $tpl_data['result'] = $lng['avatar_deleted'];
                    } elseif (isset($_GET['delphoto']) && file_exists(ROOTPATH . 'files/users/cover/' . $thisUser['id'] . '_small.png')) {
                        // remove photo
                        unlink(ROOTPATH . 'files/users/cover/' . $thisUser['id'] . '.jpg');
                        unlink(ROOTPATH . 'files/users/cover/' . $thisUser['id'] . '_small.jpg');
                        $tpl_data['result'] = $lng['photo_deleted'];
                    } elseif (IS_POST) {
                        // accept data from the form, check and write to the database
                        $thisUser['live'] = isset($_POST['live']) ? functions::checkin(mb_substr($_POST['live'], 0, 100)) : '';
                        $thisUser['about'] = isset($_POST['about']) ? functions::checkin(mb_substr($_POST['about'], 0, 500)) : '';
                        $thisUser['status'] = isset($_POST['status']) ? functions::checkin(mb_substr($_POST['status'], 0, 50)) : '';
                        $thisUser['mail'] = isset($_POST['mail']) ? functions::checkin(mb_substr($_POST['mail'], 0, 40)) : '';
                        $thisUser['facebook'] = isset($_POST['facebook']) ? functions::checkin(mb_substr($_POST['facebook'], 0, 40)) : '';

                        $thisUser['rights'] = isset($_POST['rights']) ? abs(intval($_POST['rights'])) : $thisUser['rights'];
                        // carry out the necessary checks
                        if($thisUser['rights'] >= $rights || $thisUser['rights'] > 9 || $thisUser['rights'] < 0) {
                            $thisUser['rights'] = 0;
                        }
                        if ($thisUser['mail'] && filter_var($thisUser['mail'], FILTER_VALIDATE_EMAIL) === false) {
                            $error[] = 'Định dạng email không hợp lệ!';
                        }
                        if (!$error) {
                            mysql_query('UPDATE `users` SET
                                `live` = "' . mysql_real_escape_string($thisUser['live']) . '",
                                `about` = "' . mysql_real_escape_string($thisUser['about']) . '",
                                `status` = "' . mysql_real_escape_string($thisUser['status']) . '",
                                `mail` = "' . mysql_real_escape_string($thisUser['mail']) . '",
                                `facebook` = "' . mysql_real_escape_string($thisUser['facebook']) . '",
                                `rights` = "' . $thisUser['rights'] . '"
                                WHERE `id` = "' . $thisUser['id'] . '"
                            ');
                            $tpl_data['result'] = $lng['data_saved'];
                        }
                    }
                    $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                    $tpl_data['formAction'] = 'usr?mod=edit&id=' . $thisUser['id'];
                    $tpl_data['thisUser'] = [
                        'account' => functions::checkout($thisUser['account']),
                        'status' => functions::checkout($thisUser['status']),
                        'name' => functions::checkout($thisUser['imname']),
                        'live' => functions::checkout($thisUser['live']),
                        'about' => functions::checkout($thisUser['about']),
                        'mobile' => (empty($thisUser['mobile']) ? '': '0' . htmlspecialchars($thisUser['mobile'])),
                        'email' => functions::checkout($thisUser['mail']),
                        'facebook' => functions::checkout($thisUser['facebook']),
                        'rights' => (int) $thisUser['rights'],
                        'avatar' => functions::get_avatar($id),
                        'hasAvatar' => file_exists(ROOTPATH . 'files/users/avatar/' . $thisUser['id'] . '_small.png'),
                        'delAvatarUrl' => 'usr?mod=edit&id=' . $thisUser['id'] . '&delavatar',
                        'cover' => functions::getCover($id, true),
                        'hasCover' => file_exists(ROOTPATH . 'files/users/cover/' . $thisUser['id'] . '_small.jpg'),
                        'delCoverUrl' => 'usr?mod=edit&id=' . $thisUser['id'] . '&delphoto'
                    ];
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['error_rights'];
                }
                break;
        }
    } else {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['user_does_not_exist'];
    }
} else {
    $sort = isset($_GET['sort']) ? trim($_GET['sort']) : '';
    switch ($sort) {
        case 'nick':
            $sort = 'nick';
            $order = '`account` ASC';
            break;

        case 'ip':
            $sort = 'ip';
            $order = '`ip` ASC';
            break;
        default :
            $sort = 'id';
            $order = '`id` ASC';
    }
    $breadcrumb->add($lng['users']);
    $tpl_file = 'admin::usr';
    $tpl_data['tabs'] = [
        [
            'url' => 'usr',
            'name' => 'ID',
            'active' => ($sort === 'id')
        ],
        [
            'url' => 'usr?sort=nick',
            'name' => $lng['nick'],
            'active' => ($sort === 'nick')
        ],
        [
            'url' => 'usr?sort=ip',
            'name' => 'IP',
            'active' => ($sort === 'ip')
        ]
    ];
    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `preg` = "1"'), 0);
    $req = mysql_query('SELECT * FROM `users` WHERE `preg` = "1" ORDER BY ' . $order . ' LIMIT ' . $start . ', ' . $kmess);
    $tpl_data['total'] = $total;
    $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('usr?sort=' . $sort . '&page=', $start, $total, $kmess): '');
    $tpl_data['items'] = [];
    while ($res = mysql_fetch_assoc($req)) {
        $link = '';
        if ($user_id != $res['id'] && $rights > $res['rights']) {
            if ($rights >= 7) {
                $link .= '<a href="usr?mod=edit&id=' . $res['id'] . '">' . $lng['edit'] . '</a> | <a href="usr-del?id=' . $res['id'] . '">' . $lng['delete'] . '</a> | <a href="usr?mod=password&id=' . $res['id'] . '">' . $lng['change_password'] . '</a> | ';
            }
            $link .= '<a href="usr?mod=ban&id=' . $res['id'] . '">' . $lng['ban_do'] . '</a>';
        }
        $tpl_data['items'][] = [
            'content' => functions::display_user($res, array(
                'lastvisit' => 1,
                'iphist'    => 1, 'header' => ('<b>ID:' . $res['id'] . '</b>'), 'sub' => $link))
        ];
    }
}

$_breadcrumb = $breadcrumb->out();
