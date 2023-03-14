<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    switch ($mod) {
        case 'edit':
            $error = false;
            $type = isset($_GET['_type']) ? intval($_GET['_type']) : 0;
            // Add / edit link
            if ($id) {
                // If the link is edited, requesting data in the database
                $req = mysql_query('SELECT * FROM `cms_ads` WHERE `id` = "' . $id . '" LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    $type = $res['type'];
                } else {
                    $error = $lng['error_wrong_data'];
                }
            } else {
                $res = array(
                    'link'       => 'http://',
                    'show'       => 0,
                    'name'       => '',
                    'color'      => '',
                    'count_link' => 0,
                    'day'        => 0,
                    'view'       => 0,
                    'type'       => 0,
                    'layout'     => 0
                );
            }
            $breadcrumb->add('/admin/ads' . ($type ? '?type=' . $type : ''), $lng['advertisement']);
            $breadcrumb->add(($id ? $lng['link_edit'] : $lng['link_add']));
            if ($error) {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $error;
            } else {
                if (IS_POST) {
                    $link = isset($_POST['link']) ? functions::checkin($_POST['link']) : '';
                    $name = isset($_POST['name']) ? functions::checkin($_POST['name']) : '';
                    $show = isset($_POST['show']);
                    $view = isset($_POST['view']) ? abs(intval($_POST['view'])) : 0;
                    $day = isset($_POST['day']) ? abs(intval($_POST['day'])) : 0;
                    $count = isset($_POST['count']) ? abs(intval($_POST['count'])) : 0;
                    $layout = isset($_POST['layout']) ? abs(intval($_POST['layout'])) : 0;
                    $type = isset($_POST['type']) ? intval($_POST['type']) : 0;
                    $color = isset($_POST['color']) ? mb_substr(trim($_POST['color']), 0, 6) : '';
                    $error = array();
                    if (!$link || !$name) {
                        $error[] = $lng['error_empty_fields'];
                    }
                    if ($type > 3 || $type < 0) {
                        $type = 0;
                    }
                    if ($color) {
                        if (preg_match('/[^\da-fA-F_]+/', $color)) {
                            $error[] = $lng['error_color'];
                        } elseif (mb_strlen($color) != 6 && mb_strlen($color) != 3) {
                            $error[] = $lng['error_color'];
                        }
                    }
                    if ($error) {
                        $tpl_file = 'page.error';
                        $tpl_data['page_content'] = functions::display_error($error);
                        $tpl_data['back_url'] = 'ads?mod=edit';
                        $tpl_data['back_text'] = $lng['repeat'];
                    } else {
                        if ($id) {
                            // Update link after editing
                            mysql_query("UPDATE `cms_ads` SET
                                `type` = '$type',
                                `view` = '$view',
                                `link` = '" . mysql_real_escape_string($link) . "',
                                `name` = '" . mysql_real_escape_string($name) . "',
                                `color` = '$color',
                                `count_link` = '$count',
                                `day` = '$day',
                                `layout` = '$layout',
                                `show` = '$show'
                                WHERE `id` = '$id'
                            ");
                        } else {
                            // Adding a new link
                            $req = mysql_query("SELECT `mesto` FROM `cms_ads` ORDER BY `mesto` DESC LIMIT 1");
                            if (mysql_num_rows($req) > 0) {
                                $res = mysql_fetch_array($req);
                                $mesto = $res['mesto'] + 1;
                            } else {
                                $mesto = 1;
                            }
                            mysql_query("INSERT INTO `cms_ads` SET
                                `type` = '$type',
                                `view` = '$view',
                                `mesto` = '$mesto',
                                `link` = '" . mysql_real_escape_string($link) . "',
                                `name` = '" . mysql_real_escape_string($name) . "',
                                `color` = '$color',
                                `count_link` = '$count',
                                `day` = '$day',
                                `layout` = '$layout',
                                `to` = '0',
                                `show` = '$show',
                                `time` = '" . SYSTEM_TIME . "'
                            ");
                        }
                        mysql_query('UPDATE `users` SET `lastpost` = "' . SYSTEM_TIME . '" WHERE `id` = "' . $user_id . '"');
                        $tpl_file = 'page.success';
                        $tpl_data['page_content'] = ($id ? $lng['link_edit_ok'] : $lng['link_add_ok']);
                        $tpl_data['back_url'] = 'ads?type=' . $type;
                        $tpl_data['back_text'] = $lng['continue'];
                     }
                } else {
                    // Form add / change links
                    $tpl_file = 'admin::ads.edit';
                    $tpl_data['form_action'] = 'ads?mod=edit' . ($id ? '&amp;id=' . $id : '') . ($type ? '&amp;_type=' . $type : '');
                    $tpl_data['data'] = [
                        'link' => functions::checkout($res['link']),
                        'show' => $res['show'],
                        'name' => functions::checkout($res['name']),
                        'color' => $res['color'],
                        'count_link' => $res['count_link'],
                        'day' => $res['day'],
                        'view' => $res['view'],
                        'type' => $res['type'],
                        'layout' => $res['layout']
                    ];
                    $tpl_data['submit_text'] = ($id ? $lng['edit'] : $lng['add']);
                }
            }
            break;

        case 'down':
            // Moves to step down
            $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'ads';
            if ($id) {
                $req = mysql_query('SELECT `mesto`, `type` FROM `cms_ads` WHERE `id` = "' . $id . '" LIMIT 1');
                if (mysql_num_rows($req) > 0) {
                    $res = mysql_fetch_array($req);
                    $mesto = $res['mesto'];
                    $req = mysql_query('SELECT * FROM `cms_ads` WHERE `mesto` > "' . $mesto . '" AND `type` = "' . $res['type'] . '" ORDER BY `mesto` ASC LIMIT 1');
                    if (mysql_num_rows($req) > 0) {
                        $res = mysql_fetch_array($req);
                        $id2 = $res['id'];
                        $mesto2 = $res['mesto'];
                        mysql_query('UPDATE `cms_ads` SET `mesto` = "' . $mesto2 . '" WHERE `id` = "' . $id . '"');
                        mysql_query('UPDATE `cms_ads` SET `mesto` = "' . $mesto . '" WHERE `id` = "' . $id2 . '"');
                    }
                }
            }
            header('Location: ' . $ref); exit;
            break;

        case 'up':
            // Move up to the position
            $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'ads';
            if ($id) {
                $req = mysql_query('SELECT `mesto`, `type` FROM `cms_ads` WHERE `id` = "' . $id . '" LIMIT 1');
                if (mysql_num_rows($req) > 0) {
                    $res = mysql_fetch_array($req);
                    $mesto = $res['mesto'];
                    $req = mysql_query('SELECT * FROM `cms_ads` WHERE `mesto` < "' . $mesto . '" AND `type` = "' . $res['type'] . '" ORDER BY `mesto` DESC LIMIT 1');
                    if (mysql_num_rows($req) > 0) {
                        $res = mysql_fetch_array($req);
                        $id2 = $res['id'];
                        $mesto2 = $res['mesto'];
                        mysql_query('UPDATE `cms_ads` SET `mesto` = "' . $mesto2 . '" WHERE `id` = "' . $id . '"');
                        mysql_query('UPDATE `cms_ads` SET `mesto` = "' . $mesto . '" WHERE `id` = "' . $id2 . '"');
                    }
                }
            }
            header('Location: ' . $ref); exit;
            break;

        case 'del':
            // remove link
            $breadcrumb->add('/admin/ads', $lng['advertisement']);
            $breadcrumb->add($lng['delete']);
            if ($id) {
                $type = isset($_GET['_type']) ? intval($_GET['_type']) : 0;
                if (IS_POST && TOKEN_VALID) {
                    mysql_query('DELETE FROM `cms_ads` WHERE `id` = "' . $id . '"');
                    header('Location: ads?type=' . $type); exit;
                } else {
                    $tpl_file = 'page.confirm';
                    $tpl_data['form_action'] = 'ads?mod=del&amp;id=' . $id . '&_type=' . $type;
                    $tpl_data['confirm_text'] = $lng['link_deletion_warning'];
                    $tpl_data['cancel_url'] = 'ads?type=' . $type;
                }
            } else {
                header('Location: ads'); exit;
            }
            break;

        case 'clear':
            // Cleaning the base of inactive links
            $breadcrumb->add('/admin/ads', $lng['advertisement']);
            $breadcrumb->add($lng['links_delete_hidden']);
            if (IS_POST && TOKEN_VALID) {
                mysql_query('DELETE FROM `cms_ads` WHERE `to` = "1"');
                mysql_query('OPTIMIZE TABLE `cms_ads`');
                header('location: ads'); exit;
            } else {
                $tpl_file = 'page.confirm';
                $tpl_data['form_action'] = 'ads?mod=clear';
                $tpl_data['confirm_text'] = $lng['link_clear_warning'];
                $tpl_data['cancel_url'] = 'ads';
            }
            break;

        case 'show':
            // Restore / hide link
            $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'ads';
            if ($id) {
                $req = mysql_query('SELECT `to` FROM `cms_ads` WHERE `id` = "' . $id . '"');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    mysql_query('UPDATE `cms_ads` SET `to` = "' . ($res['to'] ? 0 : 1) . '" WHERE `id` = "' . $id . '"');
                }
            }
            header('Location: ' . $ref); exit;
            break;

        default:
            $breadcrumb->add($lng['advertisement']);
            $tpl_file = 'admin::ads';
            // Main menu advertising management module
            $array_type = array(
                $lng['links_armt_over_logo'],
                $lng['links_armt_under_usermenu'],
                $lng['links_armt_over_counters'],
                $lng['links_armt_under_counters']
            );
            $array_placing = array(
                $lng['link_add_placing_all'],
                $lng['link_add_placing_front'],
                $lng['link_add_placing_child']
            );
            $array_show = array(
                $lng['to_all'],
                $lng['to_guest'],
                $lng['to_users']
            );
            $type = isset($_GET['type']) ? abs(intval($_GET['type'])) : 0;
            $tpl_data['tabs'] = [
                [
                    'url' => 'ads',
                    'name' => $lng['links_armt_over_logo'],
                    'active' => !in_array($type, [1, 2, 3])
                ],
                [
                    'url' => 'ads?type=1',
                    'name' => $lng['links_armt_under_usermenu'],
                    'active' => ($type == 1)
                ],
                [
                    'url' => 'ads?type=2',
                    'name' => $lng['links_armt_over_counters'],
                    'active' => ($type == 2)
                ],
                [
                    'url' => 'ads?type=3',
                    'name' => $lng['links_armt_under_counters'],
                    'active' => ($type == 3)
                ]
            ];
            $array_menu = array(
                (!$type ? $lng['links_armt_over_logo'] : '<a href="ads">' . $lng['links_armt_over_logo'] . '</a>'),
                ($type == 1 ? $lng['links_armt_under_usermenu'] : '<a href="ads?type=1">' . $lng['links_armt_under_usermenu'] . '</a>'),
                ($type == 2 ? $lng['links_armt_over_counters'] : '<a href="ads?type=2">' . $lng['links_armt_over_counters'] . '</a>'),
                ($type == 3 ? $lng['links_armt_under_counters'] : '<a href="ads?type=3">' . $lng['links_armt_under_counters'] . '</a>')
            );
            $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_ads` WHERE `type` = '$type'"), 0);
            $tpl_data['total'] = $total;
            $tpl_data['items'] = [];
            if ($total) {
                $req = mysql_query('SELECT * FROM `cms_ads` WHERE `type` = "' . $type . '" ORDER BY `mesto` ASC LIMIT ' . $start . ', ' . $kmess);
                while ($res = mysql_fetch_assoc($req)) {
                    $name = str_replace('|', '; ', $res['name']);
                    $name = functions::checkout($name);
                    // If you have set the color, apply
                    if (!empty($res['color'])) {
                        $name = '<span style="color:#' . $res['color'] . '">' . $name . '</span>';
                    }
                    // Print the sponsored link with attributes
                    $menu = array(
                        '<a href="ads?mod=up&amp;id=' . $res['id'] . '">' . $lng['up'] . '</a>',
                        '<a href="ads?mod=down&amp;id=' . $res['id'] . '">' . $lng['down'] . '</a>',
                        '<a href="ads?mod=edit&amp;id=' . $res['id'] . '&_type=' . $type . '">' . $lng['edit'] . '</a>',
                        '<a href="ads?mod=del&amp;id=' . $res['id'] . '&_type=' . $type . '">' . $lng['delete'] . '</a>',
                        '<a href="ads?mod=show&amp;id=' . $res['id'] . '">' . ($res['to'] ? $lng['to_show'] : $lng['hide']) . '</a>'
                    );
                    // calculate the terms of the contract on advertising
                    $agreement = array();
                    $remains = array();
                    if ($res['count_link']) {
                        $agreement[] = $res['count_link'] . ' ' . $lng['transitions_n'];
                        $remains_count = $res['count_link'] - $res['count'];
                        if ($remains_count > 0) {
                            $remains[] = $remains_count . ' ' . $lng['transitions_n'];
                        }
                    }
                    if ($res['day']) {
                        $agreement[] = functions::timecount($res['day'] * 86400);
                        $remains_count = $res['day'] * 86400 - (SYSTEM_TIME - $res['time']);
                        if ($remains_count > 0) {
                            $remains[] = functions::timecount($remains_count);
                        }
                    }
                    $tpl_data['items'][] = [
                        'name' => $name,
                        'link' => functions::checkout($res['link']),
                        'running' => ($res['to'] ? false: true),
                        'count' => $res['count'],
                        'menu' => $menu,
                        'installation_date' => functions::display_date($res['time']),
                        'placing' => $array_placing[$res['layout']],
                        'to_show' => $array_show[$res['view']],
                        'agreement' => $agreement,
                        'remains' => $remains,
                        'show' => $res['show']
                    ];
                }
            }
            $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('ads?type=' . $type . '&page=', $start, $total, $kmess) : '');
    }
}
$_breadcrumb = $breadcrumb->out();
