<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    switch ($mod) {
        case 'new':
            $breadcrumb->add('/admin/ipban', $lng['ip_ban']);
            $breadcrumb->add($lng['ban_do']);
            /*
            -----------------------------------------------------------------
            Баним IP адрес
            -----------------------------------------------------------------
            */
            if (IS_POST) {
                $error = '';
                $get_ip = isset($_POST['ip']) ? trim($_POST['ip']) : '';
                $ban_term = isset($_POST['term']) ? intval($_POST['term']) : 1;
                $reason = isset($_POST['reason']) ? functions::checkin($_POST['reason']) : '';
                if (empty($get_ip)) {
                    $error = $lng['error_address'];
                }
                if (!$error) {
                    $ip1 = 0;
                    $ip2 = 0;
                    $ipt1 = array();
                    $ipt2 = array();
                    if (strstr($get_ip, '-')) {
                        // Обрабатываем диапазон адресов
                        $array = explode('-', $get_ip);
                        $get_ip = trim($array[0]);
                        if (!core::ip_valid($get_ip)) {
                            $error[] = $lng['error_firstip'];
                        } else {
                            $ip1 = ip2long($get_ip);
                        }
                        $get_ip = trim($array[1]);
                        if (!core::ip_valid($get_ip)) {
                            $error[] = $lng['error_secondip'];
                        } else {
                            $ip2 = ip2long($get_ip);
                        }
                    } elseif (strstr($get_ip, '*')) {
                        // Обрабатываем адреса с маской
                        $array = explode('.', $get_ip);
                        for ($i = 0; $i < 4; $i++) {
                            if (!isset($array[$i]) || $array[$i] == '*') {
                                $ipt1[$i] = '0';
                                $ipt2[$i] = '255';
                            } elseif (is_numeric($array[$i]) && $array[$i] >= 0 && $array[$i] <= 255) {
                                $ipt1[$i] = $array[$i];
                                $ipt2[$i] = $array[$i];
                            } else {
                                $error = $lng['error_address'];
                            }
                        }
                        $ip1 = ip2long($ipt1[0] . '.' . $ipt1[1] . '.' . $ipt1[2] . '.' . $ipt1[3]);
                        $ip2 = ip2long($ipt2[0] . '.' . $ipt2[1] . '.' . $ipt2[2] . '.' . $ipt2[3]);
                    } else {
                        // Обрабатываем одиночный адрес
                        if (!core::ip_valid($get_ip)) {
                            $error = $lng['error_address'];
                        } else {
                            $ip1 = ip2long($get_ip);
                            $ip2 = $ip1;
                        }
                    }
                }
                // Проверяем, не попадает ли IP администратора в диапазон
                if ((core::$ip >= $ip1 && core::$ip <= $ip2) || (core::$ip_via_proxy >= $ip1 && core::$ip_via_proxy <= $ip2)) {
                    $error = $lng['ip_ban_conflict_admin'];
                }
                if (!$error) {
                    // Проверка на конфликты адресов
                    $req = mysql_query('SELECT * FROM `cms_ban_ip` WHERE ("' . $ip1 . '" BETWEEN `ip1` AND `ip2`) OR ("' . $ip2 . '" BETWEEN `ip1` AND `ip2`) OR (`ip1` >= "' . $ip1 . '" AND `ip2` <= "' . $ip2 . '")');
                    $total = mysql_num_rows($req);
                    if ($total) {
                        $tpl_file = 'admin::ipban.conflict-list';
                        $tpl_data['total'] = $total;
                        $tpl_data['items'] = [];
                        while ($res = mysql_fetch_array($req)) {
                            $get_ip = $res['ip1'] == $res['ip2'] ? long2ip($res['ip1']) : long2ip($res['ip1']) . ' - ' . long2ip($res['ip2']);
                            switch ($res['ban_type']) {
                                case 2:
                                    $ban_type = $lng['registration'];
                                    break;

                                default:
                                    $ban_type = '<b>' . $lng['blocking'] . '</b>';
                            }
                            $tpl_data['items'][] = [
                                'ip' => $get_ip,
                                'detail_url' => 'ipban?mod=detail&amp;id=' . $res['id'],
                                'type' => $ban_type
                            ];
                        }
                    } else {
                        mysql_query('INSERT INTO `cms_ban_ip` SET
                        `ip1` = "' . $ip1 . '",
                        `ip2` = "' . $ip2 . '",
                        `ban_type` = "' . $ban_term . '",
                        `who` = "' . $login . '",
                        `reason` = "' . mysql_real_escape_string($reason) . '",
                        `date` = "' . SYSTEM_TIME . '"');
                        header('Location: ipban'); exit;
                    }
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = functions::display_error($error);
                    $tpl_data['back_url'] = 'ipban?mod=new';
                    $tpl_data['back_text'] = $lng['back'];
                }
            } else {
                // Форма ввода IP адреса для Бана
                $tpl_file = 'admin::ipban.new';
            }
            break;

        case 'clear':
            $breadcrumb->add('/admin/ipban', $lng['ip_ban']);
            $breadcrumb->add($lng['ip_ban_clean']);

            // Очистка таблицы банов по IP
            if (IS_POST && TOKEN_VALID) {
                mysql_query('TRUNCATE TABLE `cms_ban_ip`');
                header('Location: ipban'); exit;
            } else {
                $tpl_file = 'page.confirm';
                $tpl_data['form_action'] = 'ipban?mod=clear';
                $tpl_data['confirm_text'] = $lng['ip_ban_clean_warning'];
                $tpl_data['cancel_url'] = 'ipban';
            }
            break;

        case 'detail':
            $breadcrumb->add('/admin/ipban', $lng['ip_ban']);
            $breadcrumb->add($lng['ban_details']);
            // Вывод подробностей заблокированного адреса
            $error = false;
            if ($id) {
                // Поиск адреса по ссылке (ID)
                $req = mysql_query('SELECT * FROM `cms_ban_ip` WHERE `id` = "'  . $id . '" LIMIT 1');
                $get_ip = '';
            } elseif (isset($_GET['ip'])) {
                // Поиск адреса по запросу из формы
                $get_ip = ip2long($_GET['ip']);
                if ($get_ip) {
                    $req = mysql_query('SELECT * FROM `cms_ban_ip` WHERE "' . $get_ip . '" BETWEEN `ip1` AND `ip2` LIMIT 1');
                } else {
                    $error = $lng['error_address'];
                }
            } else {
                $error = $lng['error_address'];
            }
            if (!$error) {
                if (!mysql_num_rows($req)) {
                    $error = $lng['ip_search_notfound'];
                }
            }
            if ($error) {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $error;
            } else {
                $res = mysql_fetch_array($req);
                $get_ip = $res['ip1'] == $res['ip2'] ? '<b>' . long2ip($res['ip1']) . '</b>' : '[<b>' . long2ip($res['ip1']) . '</b>] - [<b>' . long2ip($res['ip2']) . '</b>]';
                switch ($res['ban_type']) {
                    case 2:
                        $ban_type = $lng['registration'];
                        break;

                    default:
                        $ban_type = $lng['blocking'];
                }
                $tpl_file = 'admin::ipban.detail';
                $tpl_data['ban_ip'] = $get_ip;
                $tpl_data['ban_type'] = $ban_type;
                $tpl_data['ban_reason'] = (empty($res['reason']) ? $lng['not_specified'] : htmlspecialchars($res['reason']));
                $tpl_data['ban_who'] = $res['who'];
                $tpl_data['ban_date'] = date('d.m.Y', $res['date']);
                $tpl_data['ban_time'] = date('H:i:s', $res['date']);
                $tpl_data['ban_del_url'] = 'ipban?mod=del&amp;id=' . $res['id'];
            }
            break;

        case 'del':
            $breadcrumb->add('/admin/ipban', $lng['ip_ban']);
            $breadcrumb->add($lng['ip_ban_del']);
            /*
            -----------------------------------------------------------------
            Удаление выбранного IP из базы
            -----------------------------------------------------------------
            */
            if ($id) {
                if (IS_POST && TOKEN_VALID) {
                    mysql_query('DELETE FROM `cms_ban_ip` WHERE `id`="' . $id . '"');
                    mysql_query('OPTIMIZE TABLE `cms_ban_ip`');
                    header('Location: ipban'); exit;
                } else {
                    $tpl_file = 'page.confirm';
                    $tpl_data['form_action'] = 'ipban?mod=del&amp;id=' . $id;
                    $tpl_data['confirm_text'] = $lng['ban_del_question'];
                    $tpl_data['cancel_url'] = 'ipban?mod=detail&amp;id=' . $id;
                }
            }
            break;

        default:
            $breadcrumb->add($lng['ip_ban']);
            // Вывод общего списка забаненных IP

            $tpl_file = 'admin::ipban';
            $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_ban_ip`'), 0);
            $tpl_data['total'] = $total;
            $tpl_data['items'] = [];
            $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('ipban?page=', $start, $total, $kmess) : '');
            if ($total) {
                $req = mysql_query("SELECT * FROM `cms_ban_ip` ORDER BY `id` ASC LIMIT $start,$kmess");
                while ($res = mysql_fetch_assoc($req)) {
                    $get_ip = $res['ip1'] == $res['ip2'] ? long2ip($res['ip1']) : long2ip($res['ip1']) . ' - ' . long2ip($res['ip2']);
                    switch ($res['ban_type']) {
                        case 2:
                            $ban_type = $lng['registration'];
                            break;

                        default:
                            $ban_type = '<b>' . $lng['blocking'] . '</b>';
                    }
                    $tpl_data['items'][] = [
                        'ip' => $get_ip,
                        'detail_url' => 'ipban?mod=detail&amp;id=' . $res['id'],
                        'type' => $ban_type
                    ];
                }
            }
    }
}
$_breadcrumb = $breadcrumb->out();
