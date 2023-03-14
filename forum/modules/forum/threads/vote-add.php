<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights == 3 || $rights >= 6) {
        require(ROOTPATH . 'system/header.php');
        $topic = mysql_query('SELECT `refid`, `text`, `realid`, `thread_closed` FROM `phonho_threads` WHERE `id`="' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_deleted` != "1"') . ' LIMIT 1');
        if (mysql_num_rows($topic)) {
            $res = mysql_fetch_assoc($topic);
            $thread_url = '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url, $res['text']);
            $breadcrumb->add($lng['add_vote']);
            $_breadcrumb = $breadcrumb->out();
            $error = false;
            if ($res['thread_closed'] && $rights < RIGHTS_ADMIN) {
                $error = $lang['error_topic_closed'];
            } elseif ($res['realid']) {
                $error = $lng['error_wrong_data'];
            }
            if (!$error) {
                $res = mysql_fetch_assoc($topic);
                $poll_question = isset($_POST['poll_question']) ? functions::checkin(mb_substr($_POST['poll_question'], 0, 127)) : '';
                $poll_responses = isset($_POST['poll_response']) && is_array($_POST['poll_response']) ? $_POST['poll_response'] : array();
                $poll_responses = array_map('functions::checkin', $poll_responses);
                for ($i = 0; $i <= MAX_POLL_RESPONSE; $i++) {
                    if(isset($poll_responses[$i])) {
                        $poll_responses[$i] = trim(mb_substr($poll_responses[$i], 0, 100));
                    } else {
                        $poll_responses[$i] = '';
                    }
                }
                $tpl_data['input_responses'] = array_map('functions::checkout', $poll_responses);
                $error = array();
                if (IS_POST && TOKEN_VALID) {
                    $poll_responses = array_diff($poll_responses, array(''));
                    $poll_responses_count = count($poll_responses);
                    if (empty($poll_question)) {
                        $error[] = 'Vui lòng nhập câu hỏi cho khảo sát!';
                    }
                    if ($poll_responses_count < 2) {
                        $error[] = 'Vui lòng nhập ít nhất hai lựa chọn cho khảo sát!';
                    }

                    if (empty($error)) {
                        $values[] = '("' . mysql_real_escape_string($poll_question) . '", "' . SYSTEM_TIME . '", "1", "' . $id . '")';
                        foreach ($poll_responses as $poll_response) {
                            $values[] = '("' . mysql_real_escape_string($poll_response) . '", "0", "2", "' . $id . '")';
                        }
                        mysql_query('INSERT INTO `cms_forum_vote` (`name`, `time`, `type`, `topic`) VALUES ' . implode(', ', $values));
                        mysql_query('UPDATE `phonho_threads` SET  `realid` = "1"  WHERE `id` = "' . $id . '"');
                        $tpl_file = 'page.success';
                        $tpl_data['page_content'] = $lng['voting_added'];
                        $tpl_data['back_url'] = $thread_abs_url;
                        $tpl_data['back_text'] = $lng['back'];
                    }
                }
                if (!$tpl_file) {
                    $tpl_file = 'forum::threads.vote-add';
                    $tpl_data['error'] = ($error ? functions::display_error($error) : '');
                    $tpl_data['form_action'] = 'vote-add';
                    $tpl_data['input_question'] = functions::checkout($poll_question);
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $error;
                $tpl_data['back_url'] = $thread_abs_url;
                $tpl_data['back_text'] = $lng['back'];
            }
        }
    } else {
        $error_rights = true;
    }
} else {
    $tpl_file = 'page.error';
    $tpl_data['page_content'] = $lng['error_wrong_data'];
}