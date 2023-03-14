<?php
class CleanUser
{
    public function removeUser($clean_id)
    {
        // Удаляем историю нарушений
        mysql_query("DELETE FROM `cms_ban_users` WHERE `user_id` = '" . $clean_id . "'");
        // Удаляем историю IP
        mysql_query("DELETE FROM `cms_users_iphistory` WHERE `user_id` = '" . $clean_id . "'");
        // remove likes
        mysql_query('DELETE FROM `cms_likes` WHERE `user_id` = "' . $clean_id . '" OR `user_like` = "' . $clean_id . '"');
        // remove chat
        mysql_query('DELETE FROM `cms_chat` WHERE `uid` = "' . $clean_id . '"');
        // remove images
        mysql_query('DELETE FROM `cms_images` WHERE `user_id` = "' . $clean_id . '"');
        // remove log
        mysql_query('DELETE FROM `cms_log` WHERE `uid` = "' . $clean_id . '" OR `pid` = "' . $clean_id . '"');
        // remove paid
        mysql_query('DELETE FROM `cms_paid` WHERE `uid` = "' . $clean_id . '"');
        // remove user data
        mysql_query('DELETE FROM `cms_users_data` WHERE `user_id` = "' . $clean_id . '"');
        // remove user posts
        mysql_query('DELETE FROM `cms_profile_posts` WHERE `user_id` = "' . $clean_id . '" OR `parent_id` = "'. $clean_id . '"');
        // remove counter
        mysql_query('DELETE FROM `counter` WHERE `user` = "' . $clean_id . '"');
        // remove stats
        mysql_query('UPDATE `counter` SET `user` = "0" WHERE `user` = "' . $clean_id . '"');
        // Удаляем пользователя
        mysql_query("DELETE FROM `users` WHERE `id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем почту и контакты
     *
     * @param $clean_id
     */
    public function removeMail($clean_id)
    {
        // The user deletes a file from your mail
        $req = mysql_query("SELECT * FROM `cms_mail` WHERE (`user_id` = '" . $clean_id . "' OR `from_id` = '" . $clean_id . "') AND `file_name` != ''");

        if (mysql_num_rows($req)) {
            while ($res = mysql_fetch_assoc($req)) {
                // Remove mail files
                if (is_file(ROOTPATH . 'files/messages/' . $res['file_name'])) {
                    @unlink(ROOTPATH . 'files/messages/' . $res['file_name']);
                }
            }
        }

        mysql_query("DELETE FROM `cms_mail` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_mail` WHERE `from_id` = '" . $clean_id . "'");
    }

    public function cleanForum($clean_id)
    {
        // Скрываем темы на форуме
        mysql_query('UPDATE `phonho_threads` SET `thread_deleted` = "1", `thread_deleted_user` = "SYSTEM" WHERE `user_id` = "' . $clean_id . '"');
        // Скрываем посты на форуме
        mysql_query('UPDATE `phonho_posts` SET `post_deleted` = "1", `post_deleted_user` = "SYSTEM" WHERE `user_id` = "' . $clean_id . '"');
        // Удаляем метки прочтения на Форуме
        mysql_query('DELETE FROM `cms_forum_rdm` WHERE `user_id` = "' . $clean_id . '"');
    }

    /**
     * Удаляем все комментарии пользователя
     *
     * @param $clean_id
     */
    public function cleanComments($clean_id)
    {
        
    }

    /**
     * The recursive delete function directory files
     *
     * @param $dir
     */
    private function removeDir($dir)
    {
        if ($objs = glob($dir . '/*')) {
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDir($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }
}