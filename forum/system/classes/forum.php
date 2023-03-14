<?php
defined('_MRKEN_CMS') or die('Error: restricted access');


class forum
{
    // delete file & like
    public function del_post($id)
    {
        $req_f = mysql_query('SELECT `filename` FROM `cms_forum_files` WHERE `post` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req_f)) {
            $res_f = mysql_fetch_assoc($req_f);
            // delete files
            unlink(ROOTPATH . 'files/forum/attach/' . $res_f['filename']);
            mysql_query('DELETE FROM `cms_forum_files` WHERE `post` = "' . $id . '" LIMIT 1');
        }
        mysql_query('DELETE FROM `cms_likes` WHERE `type` = "1" AND `sub_id` = "' . $id . '"');
        // update user postforum
        $uid = mysql_result(mysql_query('SELECT `user_id` FROM `phonho_posts` WHERE `id` = "' . $id . '"'), 0);
        mysql_query('UPDATE `users` SET `postforum` = (`postforum` - 1) WHERE `id` = "' . $uid . '" AND `postforum` > 1');
        return true;
    }

    // delete post & file & like & rdm & vote & vote users & minus user postforum
    public function del_topic($id, $del_file = true)
    {
        if ($del_file) {
            $req_f = mysql_query('SELECT `filename` FROM `cms_forum_files` WHERE `topic` = "' . $id . '"');
            if (mysql_num_rows($req_f)) {
                // delete files
                while ($res_f = mysql_fetch_assoc($req_f)) {
                    unlink(ROOTPATH . 'files/forum/attach/' . $res_f['filename']);
                }
                mysql_query('DELETE FROM `cms_forum_files` WHERE `topic` = "' . $id . '"');
            }
        }
        // delete posts
        mysql_query('DELETE FROM `phonho_posts` WHERE `refid` = "' . $id . '"');
        // delete rdm
        mysql_query('DELETE FROM `cms_forum_rdm` WHERE `topic_id` = "' . $id . '"');
        // delete likes
        mysql_query('DELETE FROM `cms_likes` WHERE `type` = "1" AND `parent_id` = "' . $id . '"');
        // delete vote & vote users
        mysql_query('DELETE FROM `cms_forum_vote` WHERE `topic` = "' . $id . '"');
        mysql_query('DELETE FROM `cms_forum_vote_users` WHERE `topic` = "' . $id . '"');
        // update user postforum
        $req = mysql_query('SELECT `user_id` as `uid`, (SELECT COUNT(*) FROM `phonho_posts` WHERE `refid` = "' . $id . '" AND `user_id` = `uid`) as `posts` FROM `phonho_posts` WHERE `refid` = "' . $id . '" GROUP BY `uid`');
        while ($res = mysql_fetch_assoc($req)){
            mysql_query('UPDATE `users` SET `postforum` = (`postforum` - ' . $res['posts'] . ') WHERE `id` = "' . $res['uid'] . '" AND `postforum` > "' . $res['posts'] . '"');
        }
        return true;
    }

    public function get_parents($id) {
        global $lng;

        $res = true;
        $parent = (int) $id;
        $tree = array();
        while ($parent != 0 && $res) {
            $res = mysql_fetch_assoc(mysql_query('SELECT `type`, `refid`, `forum_name` FROM `phonho_forums` WHERE `id` = "' . $parent . '" LIMIT 1'));
            if ($res) {
                if ($res['type'] == 'r') {
                    $tree[] = array('/forum/forums/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']);
                } else {
                    if ($res['refid'] == 0) {
                        $tree[] = array('/forum/#' . functions::makeUrl($res['forum_name']) . '-' . $parent, $res['forum_name']);
                    } else {
                        $tree[] = array('/forum/categories/' . functions::makeUrl($res['forum_name']) . '.' . $parent . '/', $res['forum_name']);
                    }
                }
                $parent = (int) $res['refid'];
            }
        }
        $tree[] = array('/forum/', $lng['forum']);
        krsort($tree);

        return $tree;
    }
}