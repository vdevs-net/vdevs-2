<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add($lng['users_clean']);
$_breadcrumb = $breadcrumb->out();

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
    if (IS_POST) {
        $req = mysql_query("SELECT `id`
            FROM `users`
            WHERE `datereg` < '" . (SYSTEM_TIME - 2592000 * 6) . "'
            AND `lastdate` < '" . (SYSTEM_TIME - 2592000 * 5) . "'
            AND `postforum` = '0'
            AND `komm` < '10'
        ");

        if (mysql_num_rows($req)) {
            $del = new CleanUser;

            // Удаляем всю информацию
            while ($res = mysql_fetch_assoc($req)) {
                $del->removeMail($res['id']);       // Удаляем почту
                $del->cleanComments($res['id']);    // Удаляем комментарии
                $del->removeUser($res['id']);       // Удаляем пользователя
                mysql_query("DELETE FROM `cms_forum_rdm` WHERE `user_id` = '" . $res['id'] . "'");
            }

            mysql_query("
                OPTIMIZE TABLE
                `users`,
                `cms_mail`,
                `cms_forum_rdm`
            ");
        }
        $tpl_file = 'page.success';
        $tpl_data['page_content'] = $lng['dead_profiles_deleted'];
        $tpl_data['back_url'] = SITE_URL . '/admin/';
        $tpl_data['back_text'] = $lng['continue'];
    } else {
        $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `users`
            WHERE `datereg` < '" . (SYSTEM_TIME - 2592000 * 6) . "'
            AND `lastdate` < '" . (SYSTEM_TIME - 2592000 * 5) . "'
            AND `postforum` = '0'
            AND `komm` < '10'
        "), 0);
        $tpl_file = 'admin::usr-clean';
        $tpl_data['total'] = $total;
    }
}
