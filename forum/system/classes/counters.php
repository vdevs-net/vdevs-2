<?php
defined('_MRKEN_CMS') or die('Restricted access');

class counters
{

    // statistics Forum
    static function forum()
    {
        $file = ROOTPATH . 'files/system/cache/count_forum.dat';
        if (file_exists($file) && filemtime($file) > (SYSTEM_TIME - 600)) {
            $res = unserialize(file_get_contents($file));
            $top = $res['top'];
            $msg = $res['msg'];
			$fls = $res['files'];
        } else {
            $top = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `thread_deleted` = "0"'), 0);
            $msg = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_posts` WHERE `post_deleted` = "0"'), 0);
			$fls = mysql_result(mysql_query('SELECT COUNT(*) FROM `cms_forum_files` WHERE `del` != "1"'), 0);
            file_put_contents($file, serialize(array('top' => $top, 'msg' => $msg, 'files' => $fls)), LOCK_EX);
        }

        return array('threads' => $top, 'messages' => $msg, 'files' => $fls);
    }

    /*
    -----------------------------------------------------------------
    Counter unread topics on the forum
    -----------------------------------------------------------------
    $mod = 0   Returns the number of unread
    $mod = 1   Displays links to unread
    -----------------------------------------------------------------
    */
    static function forum_new()
    {
        if (core::$user_id) {
            $total = mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads`
                LEFT JOIN `cms_forum_rdm` ON `phonho_threads`.`id` = `cms_forum_rdm`.`topic_id` AND `cms_forum_rdm`.`user_id` = "' . core::$user_id . '"
                WHERE' . (core::$user_rights >= 7 ? '' : ' `phonho_threads`.`thread_deleted` != "1" AND') . '
                (`cms_forum_rdm`.`topic_id` Is Null
                OR `phonho_threads`.`time` > `cms_forum_rdm`.`time`)'), 0);
            return $total;
        } else {
            return 0;
        }
    }

    /*
    -----------------------------------------------------------------
    The counter of visitors online
    -----------------------------------------------------------------
    */
    static function online()
    {
        $file = ROOTPATH . 'files/system/cache/count_online.dat';
        if (file_exists($file) && filemtime($file) > (SYSTEM_TIME - 10)) {
            $res = unserialize(file_get_contents($file));
            $users = $res['users'];
            $guests = $res['guests'];
        } else {
            $users = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `lastdate` > '" . (SYSTEM_TIME - 300) . "'"), 0);
            $guests = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > '" . (SYSTEM_TIME - 300) . "'"), 0);
            file_put_contents($file, serialize(array('users' => $users, 'guests' => $guests)), LOCK_EX);
        }
        return (core::$user_id || core::$system_set['active'] ? '<a href="' . SITE_URL . '/users/online">' . $users . ' / ' . $guests . '</a>' : core::$lng['online'] . ': ' . $users . ' / ' . $guests);
    }

    /*
    -----------------------------------------------------------------
    Number of registered users
    -----------------------------------------------------------------
    */
    static function users()
    {
        $file = ROOTPATH . 'files/system/cache/count_users.dat';
        if (file_exists($file) && filemtime($file) > (SYSTEM_TIME - 600)) {
            $res = unserialize(file_get_contents($file));
            $total = $res['total'];
            $new = $res['new'];
        } else {
            $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `users`"), 0);
            $new = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `datereg` > '" . (SYSTEM_TIME - 86400) . "'"), 0);
            file_put_contents($file, serialize(array('total' => $total, 'new' => $new)), LOCK_EX);
        }
        if ($new) $total .= ' / <span class="red">+' . $new . '</span>';
        return $total;
    }
}