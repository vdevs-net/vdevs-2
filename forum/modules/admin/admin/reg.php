<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['reg_approve']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 9) {
    $error_rights = true;
} else {
    switch ($mod) {
        case 'approve':
            // Подтверждаем регистрацию выбранного пользователя
            if ($id) {
                mysql_query('UPDATE `users` SET `preg` = "1", `regadm` = "' . $login . '" WHERE `id` = "' . $id . '"');
                $tpl_file = 'page.success';
                $tpl_data['page_content'] = $lng['reg_approved'];
                $tpl_data['back_url'] = 'reg';
                $tpl_data['back_text'] = $lng['continue'];
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
                $tpl_data['back_url'] = 'reg';
                $tpl_data['back_text'] = $lng['back'];
            }
            break;

        case 'massapprove':
            // Подтверждение всех регистраций
            mysql_query('UPDATE `users` SET `preg` = "1", `regadm` = "' . $login . '" WHERE `preg` = "0"');
            $tpl_file = 'page.success';
            $tpl_data['page_content'] = $lng['reg_approved'];
            $tpl_data['back_url'] = 'reg';
            $tpl_data['back_text'] = $lng['continue'];
            break;

        case 'del':
            // даляем выбранного пользователя
            if ($id) {
                $req = mysql_query('SELECT `id` FROM `users` WHERE `id` = "' . $id . '" AND `preg` = "0" LIMIT 1');
                if (mysql_num_rows($req)) {
                    mysql_query('DELETE FROM `users` WHERE `id` = "' . $id . '"');
                    mysql_query('DELETE FROM `cms_users_iphistory` WHERE `user_id` = "' . $id . '"');
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['user_deleted'];
                    $tpl_data['back_url'] = 'reg';
                    $tpl_data['back_text'] = $lng['continue'];
                } else {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = 'Tài khoản không tồn tại hoặc đã được duyệt!';
                    $tpl_data['back_url'] = 'reg';
                    $tpl_data['back_text'] = $lng['back'];
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
                $tpl_data['back_url'] = 'reg';
                $tpl_data['back_text'] = $lng['back'];
            }
            break;

        case 'massdel':
            // Удаление всех регистраций
            $req = mysql_query('SELECT `id` FROM `users` WHERE `preg` = "0"');
            while ($res = mysql_fetch_assoc($req)) {
                mysql_query('DELETE FROM `cms_users_iphistory` WHERE `user_id` = "' . $res['id'] . '"');
            }
            mysql_query('DELETE FROM `users` WHERE `preg` = "0"');
            mysql_query('OPTIMIZE TABLE `cms_users_iphistory` , `users`');
            $tpl_file = 'page.success';
            $tpl_data['page_content'] = $lng['reg_deleted_all'];
            $tpl_data['back_url'] = 'reg';
            $tpl_data['back_text'] = $lng['continue'];
            break;

        case 'delip':
            /*
            -----------------------------------------------------------------
            Удаляем все регистрации с заданным адресом IP
            -----------------------------------------------------------------
            */
            $ip = isset($_GET['ip']) ? intval($_GET['ip']) : false;
            if ($ip) {
                $req = mysql_query('SELECT `id` FROM `users` WHERE `preg` = "0" AND `ip` = "' . $ip . '"');
                while ($res = mysql_fetch_assoc($req)) {
                    mysql_query('DELETE FROM `cms_users_iphistory` WHERE `user_id` = "' . $res['id'] . '"');
                }
                mysql_query('DELETE FROM `users` WHERE `preg` = "0" AND `ip` = "' . $ip . '"');
                mysql_query('OPTIMIZE TABLE `cms_users_iphistory` , `users`');
                $tpl_file = 'page.success';
                $tpl_data['page_content'] = $lng['reg_del_ip_done'];
                $tpl_data['back_url'] = 'reg';
                $tpl_data['back_text'] = $lng['continue'];
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
                $tpl_data['back_url'] = 'reg';
                $tpl_data['back_text'] = $lng['back'];
            }
            break;

        default:
            // Выводим список пользователей, ожидающих подтверждения регистрации
            $tpl_file = 'admin::reg';
            $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `users` WHERE `preg` = "0"'), 0);
            $tpl_data['total'] = $total;
            $tpl_data['pagination'] = '';
            $tpl_data['items'] = [];
            if ($total) {
                if ($total > $kmess) {
                    $tpl_data['pagination'] = functions::display_pagination('reg?page=', $start, $total, $kmess);
                }
                $req = mysql_query('SELECT * FROM `users` WHERE `preg` = "0" ORDER BY `id` DESC LIMIT ' . $start . ', ' . $kmess);
                while ($res = mysql_fetch_assoc($req)) {
                    $link = array(
                        '<a href="reg?mod=approve&amp;id=' . $res['id'] . '">' . $lng['approve'] . '</a>',
                        '<a href="reg?mod=del&amp;id=' . $res['id'] . '">' . $lng['delete'] . '</a>',
                        '<a href="reg?mod=delip&amp;ip=' . $res['ip'] . '">' . $lng['reg_del_ip'] . '</a>'
                    );
                    $tpl_data['items'][] = functions::display_user($res, array(
                        'header' => '<b>ID:' . $res['id'] . '</b>',
                        'sub' => functions::display_menu($link)
                    ));
                }
            }
    }
}