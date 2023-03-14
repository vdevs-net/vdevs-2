<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights == 3 || $rights >= 6) {
        $req = mysql_query('SELECT `refid`, `text` FROM `phonho_threads` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($req)) {
            $ms = mysql_fetch_assoc($req);
            $thread_url = '/forum/threads/' .  functions::makeUrl($ms['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url, $ms['text']);
            $breadcrumb->add($lng['topic_move']);
            $_breadcrumb = $breadcrumb->out();

            if (IS_POST && TOKEN_VALID) {
                $error = false;
                $razd = isset($_POST['razd']) ? abs(intval($_POST['razd'])) : 0;
                if ($razd) {
                    $typ1 = mysql_query('SELECT `refid` FROM `phonho_forums` WHERE `id` = "' . $razd . '" AND `type` = "r" LIMIT 1');
                    if (mysql_num_rows($typ1)) {
                        mysql_query('UPDATE `phonho_threads` SET `refid` = "' . $razd . '" WHERE `id` = "' . $id . '"');
                        mysql_query('UPDATE `cms_forum_files` SET `cat` = "' . mysql_result($typ1, 0) . '", `subcat` = "' . $razd . '" WHERE `topic` = "' . $id . '"');
                        header('Location: ' . $thread_abs_url); exit;
                    } else {
                        $error = true;
                    }
                } else {
                    $error = true;
                }
                if ($error) {
                    $tpl_file = 'error';
                    $tpl_data['page_content'] = $lng['error_wrong_data'];
                    $tpl_data['back_url'] = SITE_URL . '/forum/';
                    $tpl_data['back_text'] = $lng['to_forum'];
                }
            } else {
                $tpl_file = 'forum::threads.move';
                // Moving threads
                require(ROOTPATH . 'system/header.php');
                $tpl_data['form_action'] = 'move';
                $tpl_data['options'] = '';
                $frm = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `type` = "f" ORDER BY `realid` ASC');
                while ($frm1 = mysql_fetch_assoc($frm)) {
                    $tpl_data['options'] .= '<optgroup label="' . functions::checkout($frm1['forum_name']) . '">';
                    $raz = mysql_query('SELECT `id`, `forum_name` FROM `phonho_forums` WHERE `refid` = "' . $frm1['id'] . '" AND `type` = "r" AND `id` != "' . $ms['refid'] . '" ORDER BY `realid` ASC');
                    while ($raz1 = mysql_fetch_assoc($raz)) {
                        $tpl_data['options'] .= '<option value="' . $raz1['id'] . '">' . functions::checkout($raz1['forum_name']) . '</option>';
                    }
                }
            }
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}