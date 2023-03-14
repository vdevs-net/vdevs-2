<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights == 3 || $rights >= 6) {
        $typ = mysql_query('SELECT `prefix`, `text`, `refid`, `soft`, `portal`, `sticked`, `thread_closed` FROM `phonho_threads` WHERE `id` = "' . $id . '" LIMIT 1');
        if (mysql_num_rows($typ)) {
            $ms = mysql_fetch_assoc($typ);
            $page_title = $lng['topic_edit'];
            $thread_url = '/forum/threads/' . functions::makeUrl($ms['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            if (IS_POST && TOKEN_VALID) {
                $nn = isset($_POST['nn']) ? functions::checkin(mb_substr($_POST['nn'], 0, 255)) : '';
                $prefix = isset($_POST['prefix']) ? abs(intval($_POST['prefix'])) : 0;
                if (! array_key_exists($prefix, $prefixs)) {
                    $prefix = 0;
                }
                $tags = isset($_POST['tags']) ? functions::forum_tags(functions::checkin($_POST['tags'])) : '';
                $close = isset($_POST['close']) ? 1 : 0;
                $stick = isset($_POST['stick']) ? 1 : 0;
                $portal = isset($_POST['portal']) ? 1 : 0;
                if (mb_strlen($nn) < 16) {
                    $tpl_file = 'page.error';
                    $tpl_data['page_content'] = $lng['error_topic_name_lenght'];
                    $tpl_data['back_url'] = $thread_abs_url . 'edit';
                    $tpl_data['back_text'] = $lng['repeat'];
                } else {
                    // Check whether there is a theme with the same name?
                    if (mysql_result(mysql_query('SELECT COUNT(*) FROM `phonho_threads` WHERE `refid` = "' . $ms['refid'] . '" and `text`="' . mysql_real_escape_string($nn) . '" AND `id` != "' . $id . '"'), 0)) {
                        $tpl_file = 'page.error';
                        $tpl_data['page_content'] = $lng['error_topic_exists'];
                        $tpl_data['back_url'] = $thread_abs_url . 'edit';
                        $tpl_data['back_text'] = $lng['repeat'];
                    } else {
                        mysql_query('UPDATE `phonho_threads` SET `prefix` = "' . $prefix . '", `text` = "' . mysql_real_escape_string($nn) . '", `soft`="' . mysql_real_escape_string($tags) . '", `thread_closed` = "' . $close . '", `sticked` ="' . $stick . '"' . ($rights >= RIGHTS_ADMIN ? ', `portal` = "' . $portal . '"' : '') . ' WHERE `id`="' . $id . '"');
                        header('Location: ' . SITE_URL . '/forum/threads/' . functions::makeUrl($nn) . '.' . $id . '/'); exit;
                    }
                }
            } else {
                $tpl_file = 'forum::threads.edit';
                require(ROOTPATH . 'system/header.php');
                get_breadcrumb($ms['refid'], [$thread_url, $ms['text']], $_breadcrumb);
                $tpl_data['thread_name'] = functions::checkout($ms['text']);
                // Edit topic
                $tag = '';
                if (!empty($ms['soft'])) {
                    $tags = unserialize($ms['soft']);
                    if (count($tags)) {
                        $tag = implode(', ', $tags);
                    }
                }
                $tpl_data['form_action'] = $thread_abs_url . 'edit';
                $tpl_data['thread_tags'] = functions::checkout($tag);
                $tpl_data['prefix_option'] = '';
                foreach ($prefixs as $k => $v) {
                    $tpl_data['prefix_option'] .= '<option value="' . $k . '"' . ($ms['prefix'] == $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
                }
                $tpl_data['thread_closed'] = $ms['thread_closed'];
                $tpl_data['thread_sticked'] = $ms['sticked'];
                $tpl_data['thread_portal'] = $ms['portal'];
            }
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}