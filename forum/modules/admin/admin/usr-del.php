<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['user_del']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    $user = false;
    $error = false;
    if ($id && $id != $user_id) {
        // Получаем данные юзера
        $req = mysql_query("SELECT * FROM `users` WHERE `id` = '$id' LIMIT 1");
        if (mysql_num_rows($req)) {
            $user = mysql_fetch_assoc($req);
            if ($user['rights'] > $datauser['rights'])
                $error = $lng['error_usrdel_rights'];
        } else {
            $error = $lng['error_user_not_exist'];
        }
    } else {
        $error = $lng['error_wrong_data'];
    }
    if ($error) {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $error;
    } else {
        // Считаем созданные темы на Форуме
        $forumt_count = mysql_result(mysql_query("SELECT COUNT(*) FROM `phonho_threads` WHERE `user_id` = '" . $user['id'] . "' AND `thread_deleted` != '1'"), 0);
        // Считаем посты на Форуме
        $forump_count = mysql_result(mysql_query("SELECT COUNT(*) FROM `phonho_posts` WHERE `user_id` = '" . $user['id'] . "'  AND `post_deleted` != '1'"), 0);

        if (IS_POST) {
            $del = new CleanUser;
            $del->removeMail($user['id']);          // Удаляем почту

            if (isset($_POST['comments'])) {
                $del->cleanComments($user['id']);   // Удаляем комментарии
            }

            if (isset($_POST['forum'])) {
                $del->cleanForum($user['id']);      // Чистим Форум
            }

            $del->removeUser($user['id']);          // Удаляем пользователя

            // Оптимизируем таблицы
            mysql_query("
                OPTIMIZE TABLE
                `cms_users_iphistory`,
                `cms_ban_users`,
                `cms_forum_rdm`
            ");
            $tpl_file = 'page.success';
            $tpl_data['page_content'] = $lng['user_deleted'];
        } else {
            $tpl_file = 'admin::usr-del';
            $tpl_data['this_user'] = $user;
            $tpl_data['this_user_info'] = functions::display_user($user, array(
                'lastvisit' => 1,
                'iphist'    => 1
            ));
            $tpl_data['forump_count'] = $forump_count;
            $tpl_data['forumt_count'] = $forumt_count;
            $tpl_data['formAction'] = 'usr-del?id=' . $user['id'];
            $tpl_data['profileUrl'] = SITE_URL . '/profile/' . $user['account'] . '.' . $user['id'] . '/';
        }
    }
}
