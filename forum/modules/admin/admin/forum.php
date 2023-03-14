<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    // Load the language file of the forum
    $lng = array_merge($lng, core::load_lng('forum'));

    switch ($mod) {
        case 'del':
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $breadcrumb->add('/admin/forum?mod=cat', $lng['forum_structure']);
            // Deleting a category or section
            if ($id) {
                $req = mysql_query('SELECT `refid`, `type`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $id . '" AND (`type` = "f" OR `type` = "r") LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    $breadcrumb->add(($res['type'] == 'r' ? $lng['delete_section'] : $lng['delete_catrgory']) . ': ' . functions::checkout($res['forum_name']));
                    // check if there is any subordinate information
                    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_' . ($res['type'] == 'r' ? 'threads' : 'forums') . '` WHERE `refid` = "' . $id . '"' . ($res['type'] == 'r' ? '' : ' AND `type` = "r"')), 0);
                    if ($total) {
                        if ($res['type'] == 'f') {
                            // Deleting a category with subordinate data
                            if (IS_POST) {
                                $category = isset($_POST['category']) ? abs(intval($_POST['category'])) : 0;
                                if ($category && $category != $id) {
                                    $check = mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $category . '" AND `type` = "f" LIMIT 1');
                                    if (mysql_num_rows($check)) {
                                        $cat_name = mysql_result($check, 0);
                                        // We calculate the sorting rules and movable partitions
                                        $req_s = mysql_query('SELECT `realid` FROM `phonho_forums` WHERE `refid`="' . $category . '" AND `type`="r" ORDER BY `realid` DESC LIMIT 1');
                                        if (mysql_num_rows($req_s)) {
                                            $res_s = mysql_fetch_assoc($req_s);
                                            $sortnum = $res_s['realid'] + 1;
                                        } else {
                                            $sortnum = 1;
                                        }
                                        $req_c = mysql_query('SELECT `id` FROM `phonho_forums` WHERE `refid` = "' . $id . '" AND `type` = "r"');
                                        while ($res_c = mysql_fetch_assoc($req_c)) {
                                            mysql_query('UPDATE `phonho_forums` SET `refid` = "' . $category . '", `realid` = "' . $sortnum . '" WHERE `id` = "' . $res_c['id'] . '"');
                                            ++$sortnum;
                                        }
                                        // Move files to the selected category
                                        mysql_query('UPDATE `cms_forum_files` SET `cat` = "' . $category . '" WHERE `cat` = "' . $res['refid'] . '"');
                                        mysql_query('DELETE FROM `phonho_forums` WHERE `id` = "' . $id . '"');
                                        $tpl_file = 'page.success';
                                        $tpl_data['page_content'] = $lng['category_deleted'] . '! ' . $lng['contents_moved_to'] . ' <a href="' . SITE_URL . '/forum/categories/' . functions::makeUrl($cat_name) . '.' . $category . '/">' . $lng['selected_category'] . '</a>.';
                                    } else {
                                        $tpl_file = 'page.error';
                                        $tpl_data['page_content'] = $lng['error_wrong_data'];
                                    }
                                } else {
                                    $tpl_file = 'page.error';
                                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                                }
                            } else {
                                $tpl_file = 'admin::forum.delete';
                                $tpl_data['isCategory'] = true;
                                $tpl_data['formAction'] = 'forum?mod=del&id=' . $id;
                                $tpl_data['warningText'] = $lng['contents_move_warning'];
                                $tpl_data['descriptionText'] = $lng['contents_move_description'];
                                $tpl_data['destinationName'] = 'category';
                                $tpl_data['destinations'] = [];
                                $tpl_data['otherCategories'] = [];
                                $req_c = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "f" AND `id` != "' . $id . '" ORDER BY `realid` ASC');
                                while ($res_c = mysql_fetch_assoc($req_c)) {
                                    $tpl_data['destinations'][] = [
                                        'id' => $res_c['id'],
                                        'name' => functions::checkout($res_c['forum_name'])
                                    ];
                                }
                            }
                        } else {
                            // Deleting a section with subordinate data
                            if (IS_POST) {
                                // Предварительные проверки
                                $subcat = isset($_POST['subcat']) ? intval($_POST['subcat']) : 0;
                                if ($subcat && $subcat != $id) {
                                    $check = mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $subcat . '" AND `type` = "r" LIMIT 1');
                                    if (mysql_num_rows($check)) {
                                        $subcat_name = mysql_result($check, 0);
                                        mysql_query('UPDATE `phonho_threads` SET `refid` = "' . $subcat . '" WHERE `refid` = "' . $id . '"');
                                        mysql_query('UPDATE `cms_forum_files` SET `subcat` = "' . $subcat . '" WHERE `subcat` = "' . $id . '"');
                                        mysql_query('DELETE FROM `phonho_forums` WHERE `id` = "' . $id . '"');
                                        $tpl_file = 'page.success';
                                        $tpl_data['page_content'] = $lng['section_deleted'] . '! ' . $lng['themes_moved_to'] . ' <a href="' . SITE_URL . '/forum/forums/' . functions::makeUrl($subcat_name) . '.' . $subcat . '/">' . $lng['selected_section'] . '</a>.';
                                    } else {
                                        $tpl_file = 'page.error';
                                        $tpl_data['page_content'] = $lng['error_wrong_data'];
                                    }
                                } else {
                                    $tpl_file = 'page.error';
                                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                                }
                            } elseif (isset($_POST['delete']) && $rights == 9) {
                                // Удаляем файлы
                                $req_f = mysql_query('SELECT `filename` FROM `cms_forum_files` WHERE `subcat` = "' . $id . '"');
                                while ($res_f = mysql_fetch_assoc($req_f)) {
                                    unlink(ROOTPATH . 'files/forum/attach/' . $res_f['filename']);
                                }
                                mysql_query('DELETE FROM `cms_forum_files` WHERE `subcat` = "' . $id . '"');
                                // Удаляем посты, голосования и метки прочтений
                                $req_t = mysql_query('SELECT `id` FROM `phonho_threads` WHERE `refid` = "' . $id . '"');
                                $forum = new forum();
                                while ($res_t = mysql_fetch_assoc($req_t)) {
                                    $forum->del_topic($res_t['id'], false);
                                }
                                // Удаляем темы
                                mysql_query('DELETE FROM `phonho_threads` WHERE `refid` = "' . $id . '"');
                                // Удаляем раздел
                                mysql_query('DELETE FROM `phonho_forums` WHERE `id` = "' . $id . '"');
                                // Оптимизируем таблицы
                                mysql_query('OPTIMIZE TABLE `cms_forum_files` , `cms_forum_rdm`, `cms_likes`, `phonho_threads`, `phonho_posts`, `cms_forum_vote` , `cms_forum_vote_users`');
                                $tpl_file = 'page.success';
                                $tpl_data['page_content'] = $lng['section_themes_deleted'] . '! ' .
                                    '<a href="forum?mod=cat&amp;id=' . $res['refid'] . '">' . $lng['to_category'] . '</a>.';
                            } else {
                                $tpl_file = 'admin::forum.delete';
                                $tpl_data['isCategory'] = false;
                                $tpl_data['formAction'] = 'forum?mod=del&id=' . $id;
                                $tpl_data['warningText'] = $lng['section_move_warning'];
                                $tpl_data['descriptionText'] = $lng['section_move_description'];
                                $tpl_data['destinationName'] = 'subcat';
                                $tpl_data['destinations'] = [];
                                $tpl_data['otherCategories'] = [];
                                $cat = isset($_GET['cat']) ? abs(intval($_GET['cat'])) : 0;
                                $ref = $cat ? $cat : $res['refid'];
                                $req_r = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `refid` = "' . $ref . '" AND `id` != "' . $id . '" AND `type` = "r" ORDER BY `realid` ASC');
                                while ($res_r = mysql_fetch_assoc($req_r)) {
                                    $tpl_data['destinations'][] = [
                                        'id' => $res_r['id'],
                                        'name' => functions::checkout($res_r['forum_name'])
                                    ];
                                }
                                $req_c = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "f" AND `id` != "' . $ref . '" ORDER BY `realid` ASC');
                                while ($res_c = mysql_fetch_assoc($req_c)) {
                                    $tpl_data['otherCategories'][] = [
                                        'url' => 'forum?mod=del&amp;id=' . $id . '&amp;cat=' . $res_c['id'],
                                        'name' => functions::checkout($res_c['forum_name'])
                                    ];
                                }
                            }
                        }
                    } else {
                        // Deleting an empty section, or category
                        if (IS_POST && TOKEN_VALID) {
                            mysql_query('DELETE FROM `phonho_forums` WHERE `id` = "' . $id . '"');
                            $tpl_file = 'page.success';
                            $tpl_data['page_content'] = ($res['type'] == 'r' ? $lng['section_deleted'] : $lng['category_deleted']);
                        } else {
                            $tpl_file = 'page.confirm';
                            $tpl_data['form_action'] = 'forum?mod=del&amp;id=' . $id;
                            $tpl_data['confirm_text'] = $lng['delete_confirmation'];
                            $tpl_data['cancel_url'] = 'forum?mod=cat' . ($res['type'] == 'r' ? '&id=' . $res['refid'] : '');
                        }
                    }
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
            }
            break;

        case 'add':
            // Добавление категории
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $error = false;
            if ($id) {
                // Проверяем наличие категории
                $req = mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $id . '" AND `type` = "f" LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    $breadcrumb->add('/admin/forum?mod=cat', $lng['forum_structure']);
                    $tpl_data['categoryName'] = functions::checkout($res['forum_name']);
                    $tpl_data['categoryUrl'] = 'forum?mod=cat&id=' . $id;
                } else {
                    $error = true;
                    $breadcrumb->add('/admin/forum?mod=cat', $lng['forum_structure']);
                }
            } else {
                $breadcrumb->add('/admin/forum?mod=cat', $lng['forum_structure']);
                $tpl_data['categoryName'] = '';
                $tpl_data['categoryUrl'] = '';
            }
            $breadcrumb->add(($id ? $lng['add_section'] : $lng['add_category']));
            if ($error) {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
            } else {
                if (IS_POST) {
                    // Принимаем данные
                    $name = isset($_POST['name']) ? functions::checkin($_POST['name']) : '';
                    $desc = isset($_POST['desc']) ? functions::checkin($_POST['desc']) : '';
                    $allow = isset($_POST['allow']) ? abs(intval($_POST['allow'])) : 0;
                    // Проверяем на ошибки
                    $error = array();
                    if (empty($name)) {
                        $error[] = $lng['error_empty_title'];
                    }
                    if ($name && (mb_strlen($name) < 3 || mb_strlen($name) > 100)) {
                        $error[] = $lng['title'] . ': ' . $lng['error_wrong_lenght'];
                    }
                    if ($desc && mb_strlen($desc) < 2) {
                        $error[] = $lng['error_description_lenght'];
                    }
                    if (empty($error)) {
                        // Добавляем в базу категорию
                        $req = mysql_query('SELECT `realid` FROM `phonho_forums` WHERE ' . ($id ? '`refid` = "' . $id . '" AND `type` = "r"' : '`type` = "f"') . ' ORDER BY `realid` DESC LIMIT 1');
                        if (mysql_num_rows($req)) {
                            $res = mysql_fetch_assoc($req);
                            $sort = $res['realid'] + 1;
                        } else {
                            $sort = 1;
                        }
                        mysql_query('INSERT INTO `phonho_forums` SET
                            `refid` = "' . ($id ? $id : 0) . '",
                            `type` = "' . ($id ? 'r' : 'f') . '",
                            `forum_name` = "' . mysql_real_escape_string($name) . '",
                            `forum_desc` = "' . mysql_real_escape_string($desc) . '",
                            `allow` = "' . $allow . '",
                            `realid` = "' . $sort . '"');
                        header('Location: forum?mod=cat' . ($id ? '&id=' . $id : '')); exit;
                    } else {
                        // Выводим сообщение об ошибках
                        $tpl_file = 'page.error';
                        $tpl_data['page_content'] = functions::display_error($error);
                    }
                } else {
                    // Форма ввода
                    $tpl_file = 'admin::forum.add';
                    $tpl_data['formAction'] = 'forum?mod=add' . ($id ? '&amp;id=' . $id : '');
                    $tpl_data['isAddForum'] = ($id ? true : false);
                }
            }
            break;

        case 'edit':
            // Редактирование выбранной категории, или раздела
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            if ($id) {
                $req = mysql_query('SELECT `type`, `refid`, `forum_desc`, `forum_name`, `allow` FROM `phonho_forums` WHERE `id` = "' . $id . '" AND (`type` = "f" OR `type` = "r") LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    $breadcrumb->add(($res['type'] == 'r' ? $lng['section_edit'] : $lng['category_edit']));
                        if (IS_POST) {
                            // Принимаем данные
                            $name = isset($_POST['name']) ? functions::checkin($_POST['name']) : '';
                            $desc = isset($_POST['desc']) ? functions::checkin($_POST['desc']) : '';
                            $category = isset($_POST['category']) ? abs(intval($_POST['category'])) : 0;
                            $allow = isset($_POST['allow']) ? abs(intval($_POST['allow'])) : 0;
                            // проверяем на ошибки
                            $error = array();
                            if ($res['type'] == 'r' && !$category) {
                                $error[] = $lng['error_category_select'];
                            } elseif ($res['type'] == 'r' && !mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `id` = "' . $category . '" AND `type` = "f"'), 0)) {
                                $error[] = $lng['error_category_select'];
                            }
                            if (empty($name)) {
                                $error[] = $lng['error_empty_title'];
                            }
                            if ($name && (mb_strlen($name) < 3 || mb_strlen($name) > 100)) {
                                $error[] = $lng['title'] . ': ' . $lng['error_wrong_lenght'];
                            }
                            if ($desc && mb_strlen($desc) < 2) {
                                $error[] = $lng['error_description_lenght'];
                            }
                            if (empty($error)) {
                                $sql = '';
                                if ($res['type'] == 'r' && $category != $res['refid']) {
                                    // We calculate sorting
                                    $req_s = mysql_query('SELECT `realid` FROM `phonho_forums` WHERE `refid` = "' . $category . '" AND `type` = "r" ORDER BY `realid` DESC LIMIT 1');
                                    $res_s = mysql_fetch_assoc($req_s);
                                    $sort = $res_s['realid'] + 1;
                                    // change category
                                    $sql = ', `refid` = "' . $category . '", `realid` = "' . $sort . '"';
                                    // Change category for file attachments
                                    mysql_query('UPDATE `cms_forum_files` SET `cat` = "' . $category . '" WHERE `cat` = "' . $res['refid'] . '"');
                                }
                                // Write to the database
                                mysql_query('UPDATE `phonho_forums` SET
                                    `forum_name` = "' . mysql_real_escape_string($name) . '",
                                    `forum_desc` = "' . mysql_real_escape_string($desc) . '",
                                    `allow` = "' . $allow . '"' . $sql . '
                                    WHERE `id` = "' . $id . '"');
                                header('Location: forum?mod=cat' . ($res['type'] == 'r' ? '&id=' . $res['refid'] : '')); exit;
                            } else {
                                // An error message
                                $tpl_file = 'page.error';
                                $tpl_data['page_content'] = functions::display_error($error);
                            }
                        } else {
                            $tpl_file = 'admin::forum.edit';
                            $tpl_data['formAction'] = 'forum?mod=edit&amp;id=' . $id;
                            $tpl_data['nameInput'] = functions::checkout($res['forum_name']);
                            $tpl_data['descInput'] = functions::checkout($res['forum_desc']);
                            $tpl_data['isEditForum'] = ($res['type'] == 'r');
                            $tpl_data['forumAllow'] = intval($res['allow']);
                            $tpl_data['backUrl'] = 'forum?mod=cat' . ($res['type'] == 'r' ? '&amp;id=' . $res['refid'] : '');
                            $tpl_data['categoryList'] = [];
                            if ($res['type'] == 'r') {
                                $req_c = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "f" ORDER BY `realid` ASC');
                                while ($res_c = mysql_fetch_assoc($req_c)) {
                                    $tpl_data['categoryList'][] = [
                                        'id' => $res_c['id'],
                                        'selectStatus' => ($res_c['id'] == $res['refid'] ? ' selected="selected"' : ''),
                                        'name' => functions::checkout($res_c['forum_name'])
                                    ];
                                }
                            }
                        }
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
            }
            break;

        case 'up':
            /*
            -----------------------------------------------------------------
            Перемещение на одну позицию вверх
            -----------------------------------------------------------------
            */
            if ($id) {
                $req = mysql_query('SELECT `type`, `realid`, `refid` FROM `phonho_forums` WHERE `id` = "' . $id . '" LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res1 = mysql_fetch_assoc($req);
                    $sort = $res1['realid'];
                    $req = mysql_query('SELECT `id`, `realid` FROM `phonho_forums` WHERE `type` = "' . ($res1['type'] == 'f' ? 'f' : 'r') . '" AND `realid` < "' . $sort . '" ORDER BY `realid` ASC LIMIT 1');
                    if (mysql_num_rows($req)) {
                        $res = mysql_fetch_assoc($req);
                        $id2 = $res['id'];
                        $sort2 = $res['realid'];
                        mysql_query('UPDATE `phonho_forums` SET `realid` = "' . $sort2 . '" WHERE `id` = "' . $id . '"');
                        mysql_query('UPDATE `phonho_forums` SET `realid` = "' . $sort . '" WHERE `id` = "' . $id2 . '"');
                    }
                    header('Location: forum?mod=cat' . ($res1['type'] == 'r' ? '&id=' . $res1['refid'] : '')); exit;
                } else {
                    header('Location: forum?mod=cat'); exit;
                }
            } else {
                header('Location: forum?mod=cat'); exit;
            }
            break;

        case 'down':
            /*
            -----------------------------------------------------------------
            Перемещение на одну позицию вниз
            -----------------------------------------------------------------
            */
            if ($id) {
                $req = mysql_query('SELECT `type`, `realid`, `refid` FROM `phonho_forums` WHERE `id` = "' . $id . '" LIMIT 1');
                if (mysql_num_rows($req)) {
                    $res1 = mysql_fetch_assoc($req);
                    $sort = $res1['realid'];
                    $req = mysql_query('SELECT `id`, `realid` FROM `phonho_forums` WHERE `type` = "' . ($res1['type'] == 'f' ? 'f' : 'r') . '" AND `realid` > "' . $sort . '" ORDER BY `realid` ASC LIMIT 1');
                    if (mysql_num_rows($req)) {
                        $res = mysql_fetch_assoc($req);
                        $id2 = $res['id'];
                        $sort2 = $res['realid'];
                        mysql_query('UPDATE `phonho_forums` SET `realid` = "' . $sort2 . '" WHERE `id` = "' . $id . '"');
                        mysql_query('UPDATE `phonho_forums` SET `realid` = "' . $sort . '" WHERE `id` = "' . $id2 . '"');
                    }
                    header('Location: forum?mod=cat' . ($res1['type'] == 'r' ? '&id=' . $res1['refid'] : '')); exit;
                } else {
                    header('Location: forum?mod=cat'); exit;
                }
            } else {
                header('Location: forum?mod=cat'); exit;
            }
            break;

        case 'cat':
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $breadcrumb->add($lng['forum_structure']);
            /*
            -----------------------------------------------------------------
            Управление категориями и разделами
            -----------------------------------------------------------------
            */
            if ($id) {
                // Управление разделами
                $req = mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $id . '" AND `type` = "f"');
                if (mysql_num_rows($req)) {
                    $res = mysql_fetch_assoc($req);
                    $tpl_file = 'admin::forum.cat';
                    $tpl_data['isCategoryList'] = false;
                    $tpl_data['categoryName'] = functions::checkout($res['forum_name']);
                    $tpl_data['items'] = [];
                    $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "r" AND `refid` = "' . $id . '"'), 0);
                    if ($total) {
                        $req = mysql_query('SELECT `id`, `forum_name`, `forum_desc` FROM `phonho_forums` WHERE `refid` = "' . $id . '" AND `type` = "r" ORDER BY `realid` ASC');
                        $y = 0;
                        while ($row = mysql_fetch_assoc($req))
                        {
                            ++$y;
                            $tpl_data['items'][$row['id']] = array(
                                'id'          => $row['id'],
                                'name'        => functions::checkout($row['forum_name']),
                                'url'         => SITE_URL . '/forum/forums/' . functions::makeUrl($row['forum_name']) . '.' . $row['id'] . '/',
                                'description' => (empty($row['forum_desc']) ? '' : functions::checkout($row['forum_desc'])),
                                'childCount'  => 0,
                                'menu'        => array()
                            );
                            if ($y != 1) {
                                $tpl_data['items'][$row['id']]['menu'][] = array(
                                    'url'  => 'forum?mod=up&id=' . $row['id'],
                                    'text' => $lng['up']
                                );
                            } else {
                                $tpl_data['items'][$row['id']]['menu'][] = array(
                                    'url'  => '',
                                    'text' => $lng['up']
                                );
                            }
                            if ($y != $total) {
                                $tpl_data['items'][$row['id']]['menu'][] = array(
                                    'url'  => 'forum?mod=down&id=' . $row['id'],
                                    'text' => $lng['down']
                                );
                            } else {
                                $tpl_data['items'][$row['id']]['menu'][] = array(
                                    'url'  => '',
                                    'text' => $lng['down']
                                );
                            }
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => 'forum?mod=edit&id=' . $row['id'],
                                'text' => $lng['edit']
                            );
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => 'forum?mod=del&id=' . $row['id'],
                                'text' => $lng['delete']
                            );
                        }
                    }
                    $tpl_data['addCatUrl'] = 'forum?mod=add&amp;id=' . $id;
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                }
            } else {
                $tpl_file = 'admin::forum.cat';
                $tpl_data['isCategoryList'] = true;
                $tpl_data['categoryName'] = '';
                $tpl_data['items'] = [];
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "f"'), 0);
                if ($total) {
                    // Управление категориями
                    $req = mysql_query('SELECT `id`, `forum_name`, `forum_desc` FROM `phonho_forums` WHERE `type` = "f" ORDER BY `realid` ASC');
                    $y = 0;
                    while ($row = mysql_fetch_assoc($req))
                    {
                        ++$y;
                        $tpl_data['items'][$row['id']] = array(
                            'id'          => $row['id'],
                            'name'        => functions::checkout($row['forum_name']),
                            'url'         => SITE_URL . '/forum/#' . functions::makeUrl($row['forum_name']) . '-' . $row['id'],
                            'description' => (empty($row['forum_desc']) ? '' : functions::checkout($row['forum_desc'])),
                            'childCount'  => mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "r" AND `refid` = "' . $row['id'] . '"'), 0),
                            'menu'        => array()
                        );
                        if ($y != 1) {
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => 'forum?mod=up&id=' . $row['id'],
                                'text' => $lng['up']
                            );
                        } else {
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => '',
                                'text' => $lng['up']
                            );
                        }
                        if ($y != $total) {
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => 'forum?mod=down&id=' . $row['id'],
                                'text' => $lng['down']
                            );
                        } else {
                            $tpl_data['items'][$row['id']]['menu'][] = array(
                                'url'  => '',
                                'text' => $lng['down']
                            );
                        }
                        $tpl_data['items'][$row['id']]['menu'][] = array(
                            'url'  => 'forum?mod=edit&id=' . $row['id'],
                            'text' => $lng['edit']
                        );
                        $tpl_data['items'][$row['id']]['menu'][] = array(
                            'url'  => 'forum?mod=del&id=' . $row['id'],
                            'text' => $lng['delete']
                        );
                    }
                }
                $tpl_data['addCatUrl'] = 'forum?mod=add';
            }
            break;

        case 'files':
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $types = array(
                1 => $lng['files_type_win'],
                2 => $lng['files_type_java'],
                3 => $lng['files_type_sis'],
                4 => $lng['files_type_txt'],
                5 => $lng['files_type_pic'],
                6 => $lng['files_type_arc'],
                7 => $lng['files_type_video'],
                8 => $lng['files_type_audio'],
                9 => $lng['files_type_other']
            );
            $error = false;

            // Get the ID section and prepare request
            $do = isset($_GET['do']) && intval($_GET['do']) > 0 && intval($_GET['do']) < 10 ? intval($_GET['do']) : 0;
            if ($do) {
                $breadcrumb->add('/admin/forum?mod=files', $lng['files_forum']);
                $breadcrumb->add($types[$do]);
                $tpl_file = 'admin::forum.file-list';
                // Displays a list of files desired section
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_files` WHERE `filetype` = "' . $do . '"'), 0);
                $tpl_data['total'] = $total;
                $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('forum?mod=files&do=' . $do . '&page=', $start, $total, $kmess) :'');
                $tpl_data['items'] = [];
                if ($total > 0) {
                    $req = mysql_query('SELECT `cms_forum_files`.*, `phonho_posts`.`user_id`, `phonho_posts`.`from`, `phonho_threads`.`text` AS `topicname`
                        FROM `cms_forum_files`
                        LEFT JOIN `phonho_posts` ON `cms_forum_files`.`post` = `phonho_posts`.`id`
                        LEFT JOIN `phonho_threads` ON `cms_forum_files`.`topic` = `phonho_threads`.`id`
                        WHERE `filetype` = "' . $do . '"
                        ORDER BY `time` DESC LIMIT ' . $start . ', ' . $kmess);
                    while ($res = mysql_fetch_assoc($req)) {
                        // Form a link to a file
                        $fls = @filesize(ROOTPATH . 'files/forum/attach/' . $res['filename']);
                        $fls = round($fls / 1024, 0);
                        $att_ext = strtolower(functions::format($res['filename']));
                        $pic_ext = array(
                            'jpg',
                            'jpeg',
                            'png'
                        );
                        // caculation page of post
                        $_page = ceil(mysql_result(mysql_query("SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = '" . $res['topic'] . "' AND `id`<='" . $res['post'] . "'"), 0) / $kmess);
                        $tpl_data['items'][$res['id']] = [
                            'url' => SITE_URL . '/forum/files/' . $res['id'] . '/',
                            'name' => functions::checkout($res['filename']),
                            'size' => $fls,
                            'downloaded' => $res['dlcount'],
                            'threadUrl' => SITE_URL . '/forum/threads/' . functions::makeUrl($res['topicname']) . '.' . $res['topic'] . '/page-' . $_page,
                            'threadName' => functions::checkout($res['topicname']),
                            'uploader' => $res['from']
                        ];
                        if (in_array($att_ext, $pic_ext)) {
                            $tpl_data['items'][$res['id']]['thumb'] = SITE_URL . '/thumb.php?file=' . (urlencode($res['filename']));
                            $tpl_data['items'][$res['id']]['icon'] = ($res['del'] ? '<img src="' . SITE_URL . '/assets/images/del.png" width="16" height="16" />&nbsp;' : '');
                        } else {
                            $tpl_data['items'][$res['id']]['thumb'] = false;
                            $tpl_data['items'][$res['id']]['icon'] = ($res['del'] ? '<img src="' . SITE_URL . '/assets/images/del.png" width="16" height="16" />&nbsp;' : '') . '<img src="' . SITE_URL . '/assets/images/system/' . $res['filetype'] . '.png" width="16" height="16" />&nbsp;';
                        }
                    }
                }
            } else {
                $breadcrumb->add($lng['files_forum']);
                $tpl_file = 'admin::forum.file';
                $tpl_data['fileTypes'] = [];
                for ($i = 1; $i < 10; $i++) {
                    $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_files` WHERE `filetype` = '$i'"), 0);
                    if ($count > 0) {
                        $tpl_data['fileTypes'][] = [
                            'iconUrl' => SITE_URL . '/assets/images/system/' . $i . '.png',
                            'url' => 'forum?mod=files&amp;do=' . $i,
                            'name' => $types[$i],
                            'count' => $count
                        ];
                    }
                }
            }
            break;

        case 'htopics':
            // Управление скрытыми темами форума
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $breadcrumb->add($lng['hidden_topics']);
            $sort = '';
            $link = '';
            $tpl_file = 'admin::forum.hidden-content';
            $tpl_data['filter'] = '';
            if (isset($_GET['usort'])) {
                $sort = ' AND `phonho_threads`.`user_id` = "' . abs(intval($_GET['usort'])) . '"';
                $link = '&amp;usort=' . abs(intval($_GET['usort']));
                $tpl_data['filter'] = $lng['filter_on_author'] . ' <a href="forum?mod=htopics">[x]</a>';
            }
            if (isset($_GET['rsort'])) {
                $sort = ' AND `phonho_threads`.`refid` = "' . abs(intval($_GET['rsort'])) . '"';
                $link = '&amp;rsort=' . abs(intval($_GET['rsort']));
                $tpl_data['filter'] = $lng['filter_on_section'] . ' <a href="forum?mod=htopics">[x]</a>';
            }
            if (IS_POST && $rights == 9) {
                $forum = new forum();
                $req = mysql_query('SELECT `id` FROM `phonho_threads` WHERE `thread_deleted` = "1"' . $sort);
                while ($res = mysql_fetch_assoc($req)) {
                    $forum->del_topic($res['id']);
                }
                // Удаляем темы
                $req = mysql_query('DELETE FROM `phonho_threads` WHERE `thread_deleted` = "1"' . $sort);
                header('Location: forum?mod=htopics'); exit;
            } else {
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `thread_deleted` = "1"' . $sort), 0);
                $tpl_data['total'] = $total;
                $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('forum?mod=htopics' . $link . '&page=', $start, $total, $kmess) : '');
                $tpl_data['items'] = [];
                if ($total) {
                    $req = mysql_query('SELECT `phonho_threads`.`refid`, `phonho_threads`.`time`, `phonho_threads`.`text`, `phonho_threads`.`id` AS `fid`, `phonho_threads`.`user_id` AS `id`, `phonho_threads`.`from` AS `account`, `phonho_threads`.`soft` AS `browser`, `users`.`rights`, `users`.`lastdate`, `users`.`sex`, `users`.`status`, `users`.`datereg`
                    FROM `phonho_threads` LEFT JOIN `users` ON `phonho_threads`.`user_id` = `users`.`id`
                    WHERE `phonho_threads`.`thread_deleted` = "1"' . $sort . ' ORDER BY `phonho_threads`.`id` DESC LIMIT ' . $start . ', ' . $kmess);
                    while ($res = mysql_fetch_assoc($req)) {
                        $subcat = mysql_fetch_assoc(mysql_query('SELECT `forum_name` FROM `phonho_forums` WHERE `id` = "' . $res['refid'] . '"'));
                        $ttime = '<span class="gray">(' . functions::display_date($res['time']) . ')</span>';
                        $text = '<a href="' . SITE_URL . '/forum/threads/' . functions::makeUrl($res['text']).'.' . $res['fid'] . '/"><b>' . htmlspecialchars($res['text']) . '</b></a>';
                        $text .= '<br /><small><a href="' . SITE_URL . '/forum/forums/' . functions::makeUrl($subcat['forum_name']) . '.' . $res['refid'] . '/">' . htmlspecialchars($subcat['forum_name']) . '</a></small>';
                        $subtext = '<span class="gray">' . $lng['filter_to'] . ':</span> ';
                        $subtext .= '<a href="forum?mod=htopicsrsort=' . $res['refid'] . '">' . $lng['by_section'] . '</a> | ';
                        $subtext .= '<a href="forum?mod=htopics&usort=' . $res['id'] . '">' . $lng['by_author'] . '</a>';
                        $tpl_data['items'][] = [
                            'content' =>  functions::display_user($res, array(
                                'header' => $ttime,
                                'body'   => $text,
                                'sub'    => $subtext,
                                'iphide' => true
                            ))
                        ];
                    }
                }
                $tpl_data['deleteFormAction'] = 'forum?mod=htopics' . $link;
            }
            break;

        case 'hposts':
            // Управление скрытыми постави форума
            $breadcrumb->add('/admin/forum', $lng['forum_management']);
            $breadcrumb->add($lng['hidden_posts']);
            $sort = '';
            $link = '';
            $tpl_data['filter'] = '';
            if (isset($_GET['tsort'])) {
                $sort = ' AND `phonho_posts`.`refid` = "' . abs(intval($_GET['tsort'])) . '"';
                $link = '&amp;tsort=' . abs(intval($_GET['tsort']));
                $tpl_data['filter'] = $lng['filter_on_theme'] . ' <a href="forum?mod=hposts">[x]</a>';
            } elseif (isset($_GET['usort'])) {
                $sort = ' AND `phonho_posts`.`user_id` = "' . abs(intval($_GET['usort'])) . '"';
                $link = '&amp;usort=' . abs(intval($_GET['usort']));
                $tpl_data['filter'] = $lng['filter_on_author'] . ' <a href="forum?mod=hposts">[x]</a>';
            }
            if (IS_POST && $rights == 9) {
                $forum = new forum();
                $req = mysql_query('SELECT `id` FROM `phonho_posts` WHERE `post_deleted` = "1"' . $sort);
                while ($res = mysql_fetch_assoc($req)) {
                    $forum->del_post($res['id']);
                }
                // Удаляем посты
                mysql_query('DELETE FROM `phonho_posts` WHERE `post_deleted` = "1"' . $sort);
                header('Location: forum?mod=hposts'); exit;
            } else {
                $tpl_file = 'admin::forum.hidden-content';
                $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `post_deleted` = "1"' . $sort), 0);
                $tpl_data['total'] = $total;
                $tpl_data['pagination'] = ($total > $kmess ? functions::display_pagination('forum?mod=hposts' . $link . '&page=', $start, $total, $kmess) : '');
                $tpl_data['items'] = [];
                if ($total) {
                    $req = mysql_query('SELECT `phonho_posts`.`ip`, `phonho_posts`.`time`, `phonho_posts`.`refid`, `phonho_posts`.`text`, `phonho_posts`.`user_id`, `phonho_posts`.`id` AS `fid`, `phonho_posts`.`user_id` AS `id`, `phonho_posts`.`from` AS `account`, `phonho_posts`.`soft` AS `browser`, `phonho_threads`.`id` AS `tid`, `phonho_threads`.`text` AS `tname`, `users`.`rights`, `users`.`lastdate`, `users`.`sex`, `users`.`status`, `users`.`datereg`
                    FROM `phonho_posts` LEFT JOIN `users` ON `phonho_posts`.`user_id` = `users`.`id`
                    LEFT JOIN `phonho_threads` ON `phonho_threads`.`id`=`phonho_posts`.`refid`
                    WHERE `phonho_posts`.`post_deleted` = "1"' . $sort . ' ORDER BY `phonho_posts`.`id` DESC LIMIT ' . $start . ', ' . $kmess);
                    while ($res = mysql_fetch_assoc($req)) {
                        $posttime = ' <span class="gray">(' . functions::display_date($res['time']) . ')</span>';
                        $_page = ceil(mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $res['refid'] . '" AND `id` <="' . $res['fid'] . '"'), 0) / $kmess);
                        $text = mb_substr($res['text'], 0, 500);
                        $text = functions::checkout($text, 1, 0);
                        $text = '<b>' . htmlspecialchars($res['tname']) . '</b> <a href="' . SITE_URL . '/forum/threads/'.functions::makeUrl($res['tname'])	.'.' . $res['tid'] . '/page-' . $_page . '">&gt;&gt;</a><br />' . $text;
                        $subtext = '<span class="gray">' . $lng['filter_to'] . ':</span> ';
                        $subtext .= '<a href="forum?mod=hposts&amp;tsort=' . $res['tid'] . '">' . $lng['by_theme'] . '</a> | ';
                        $subtext .= '<a href="forum?mod=hposts&amp;usort=' . $res['user_id'] . '">' . $lng['by_author'] . '</a>';
                        $tpl_data['items'][] = [
                            'content' => functions::display_user($res, array(
                                'header' => $posttime,
                                'body'   => $text,
                                'sub'    => $subtext,
                                'iphide' => true
                            ))
                        ];
                    }
                }
                $tpl_data['deleteFormAction'] = 'forum?mod=hposts' . $link;
            }
            break;

        default:
            // Control Panel Forum
            $breadcrumb->add($lng['forum_management']);
            $tpl_data['total_cat'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "f"'), 0);
            $tpl_data['total_sub'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_forums` WHERE `type` = "r"'), 0);
            $tpl_data['total_thm'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`'), 0);
            $tpl_data['total_thm_del'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `thread_deleted` = "1"'), 0);
            $tpl_data['total_msg'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts`'), 0);
            $tpl_data['total_msg_del'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `post_deleted` = "1"'), 0);
            $tpl_data['total_files'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_files`'), 0);
            $tpl_data['total_votes'] = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type` = "1"'), 0);
            $tpl_file = 'admin::forum';
    }

} // end else $rights
$_breadcrumb = $breadcrumb->out();
