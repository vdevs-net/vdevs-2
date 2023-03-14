<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    $error = false;
    // Download the attached file Forum
    $req = mysql_query('SELECT `filename`, `dlcount` FROM `cms_forum_files` WHERE `id` = "' . $id . '" AND `del`="0" LIMIT 1');
    if (mysql_num_rows($req)) {
        $res = mysql_fetch_array($req);
        $file = ROOTPATH . 'files/forum/attach/' . $res['filename'];
        if (file_exists($file)) {
            $dlcount = $res['dlcount'] + 1;
            mysql_query('UPDATE `cms_forum_files` SET  `dlcount` = "'. $dlcount .'" WHERE `id` = "'. $id .'"');
            header('Location: ' . SITE_URL . '/files/forum/attach/' . $res['filename']); exit;
        } else {
            mysql_query('DELETE FROM `cms_forum_files` WHERE `id` = "' . $id . '"');
            $error = true;
        }
    } else {
        $error = true;
    }
    if ($error) {
        $tpl_file = 'page.error';
        $tpl_data['page_content'] = $lng['error_file_not_exist'];
        $tpl_data['back_url'] = SITE_URL . '/forum/';
        $tpl_data['back_text'] = $lng['to_forum'];
    }
}