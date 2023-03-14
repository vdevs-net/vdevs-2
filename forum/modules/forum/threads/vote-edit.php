<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

if ($id) {
    if ($rights == 3 || $rights >= 6) {
        $topic = mysql_query('SELECT `text`, `realid` FROM `phonho_threads` WHERE `id`="' . $id . '"' . ($rights >= RIGHTS_ADMIN ? '' : ' AND `thread_closed` != "1"') . ' LIMIT 1');
        if (mysql_num_rows($topic)) {
            $res = mysql_fetch_assoc($topic);
            $page_title = $lng['edit_vote'];
            $thread_url = '/forum/threads/' . functions::makeUrl($res['text']) . '.' . $id . '/';
            $thread_abs_url = SITE_URL . $thread_url;
            $breadcrumb = new breadcrumb(0, 1);
            $breadcrumb->add($thread_url, $res['text']);
            $breadcrumb->add($lng['edit_vote']);
            $_breadcrumb = $breadcrumb->out();

            if ($res['realid']) {
                $poll_question = isset($_POST['poll_question']) ? functions::checkin(mb_substr($_POST['poll_question'], 0, 127)) : '';
                if (isset($_GET['delvote'])) {
                    $delvote = abs(intval($_GET['delvote']));
                    if ($delvote) {
                        $poll_option = mysql_query("SELECT `count` FROM `cms_forum_vote` WHERE `type` = '2' AND `id` = '$delvote' AND `topic` = '$id'");
                        if (mysql_num_rows($poll_option)) {
                            $countvote = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type` = '2' AND `topic` = '$id'"), 0);
                            if ($countvote > 2) {
                                if (IS_POST && TOKEN_VALID) {
                                    mysql_query("DELETE FROM `cms_forum_vote` WHERE `id` = '$delvote'");
                                    $countus = mysql_result($poll_option, 0);
                                    $topic_vote = mysql_fetch_assoc(mysql_query("SELECT `count` FROM `cms_forum_vote` WHERE `type` = '1' AND `topic` = '$id' LIMIT 1"));
                                    $totalcount = $topic_vote['count'] - $countus;
                                    mysql_query("UPDATE `cms_forum_vote` SET  `count` = '$totalcount'   WHERE `type` = '1' AND `topic` = '$id'");
                                    mysql_query("DELETE FROM `cms_forum_vote_users` WHERE `vote` = '$delvote'");
                                    header('Location: vote-edit'); exit;
                                } else {
                                    require(ROOTPATH . 'system/header.php');
                                    $tpl_file = 'page.confirm';
                                    $tpl_data['form_action'] = 'vote-edit?delvote=' . $delvote;
                                    $tpl_data['confirm_text'] = $lng['voting_variant_warning'];
                                    $tpl_data['cancel_url'] = 'vote-edit';
                                }
                            }
                        }
                    }
                    if (!$tpl_file) {
                        $tpl_file = 'page.error';
                        $tpl_data['page_content'] = $lng['error_wrong_data'];
                        $tpl_data['back_url'] = $thread_abs_url . 'vote-edit';
                        $tpl_data['back_text'] = $lng['back'];
                    }
                } elseif (IS_POST && TOKEN_VALID) {
                    // get existing response
                    $existing_responses = isset($_POST['existing_response']) && is_array($_POST['existing_response']) ? $_POST['existing_response'] : array();
                    $existing_responses = array_map('functions::checkin', $existing_responses);
                    $existing_responses = array_diff($existing_responses, array(''));
                    // get new responses
                    $new_responses = isset($_POST['new_response']) && is_array($_POST['new_response']) ? $_POST['new_response'] : array();
                    $new_responses = array_map('functions::checkin', $new_responses);
                    $new_responses = array_diff($new_responses, array(''));
                    if (!empty($poll_question)) {
                        mysql_query("UPDATE `cms_forum_vote` SET  `name` = '" . mysql_real_escape_string($poll_question) . "'  WHERE `topic` = '$id' AND `type` = '1'");
                    }
                    $vote_result = mysql_query("SELECT `id`, `name` FROM `cms_forum_vote` WHERE `type`='2' AND `topic`='" . $id . "'") or die(mysql_error());
                    $countvote = mysql_num_rows($vote_result);
                    while ($vote = mysql_fetch_assoc($vote_result)) {
                        if (isset($existing_responses[$vote['id']]) && $existing_responses[$vote['id']] != $vote['name']) {
                            $text = trim(mb_substr($existing_responses[$vote['id']], 0, 100));
                            mysql_query("UPDATE `cms_forum_vote` SET  `name` = '" . mysql_real_escape_string($text) . "'  WHERE `id` = '" . $vote['id'] . "'");
                        }
                    }
                    $insert = array();
                    for ($i = 0; $i < (MAX_POLL_RESPONSE - $countvote); $i++) {
                        if (isset($new_responses[$i])) {
                            $text = trim(mb_substr($new_responses[$i], 0, 100));
                            $insert[] = '("' . mysql_real_escape_string($text) . '", "2", "' . $id . '")';
                        }
                    }
                    if ($insert) {
                        mysql_query('INSERT INTO `cms_forum_vote` (`name`, `type`, `topic`) VALUES ' . implode(', ', $insert));
                    }
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['voting_changed'];
                    $tpl_data['back_url'] = $thread_abs_url;
                    $tpl_data['back_text'] = $lng['continue'];
                } else {
                    $tpl_file = 'forum::threads.vote-edit';
                    // Editing form survey
                    $topic_vote = mysql_result(mysql_query("SELECT `name` FROM `cms_forum_vote` WHERE `type` = '1' AND `topic` = '$id' LIMIT 1"), 0);
                    $vote_results = mysql_query("SELECT `id`, `name` FROM `cms_forum_vote` WHERE `type` = '2' AND `topic` = '$id'");
                    $countvote = mysql_num_rows($vote_results);
                    $tpl_data['form_action'] = 'vote-edit';
                    $tpl_data['poll_question'] = functions::checkout($topic_vote);
                    while ($vote = mysql_fetch_array($vote_results)) {
                        $tpl_data['poll_responses'][] = [
                            'id' => $vote['id'],
                            'text' => functions::checkout($vote['name']),
                            'delete_url' => ($countvote > 2 ? 'vote-edit?delvote=' . $vote['id'] : '')
                        ];
                    }
                    $new_responses = isset($_POST['new_response']) && is_array($_POST['new_response']) ? $_POST['new_response'] : array();
                    $new_responses = array_map('functions::checkin', $new_responses);
                    for ($i = 0; $i < (MAX_POLL_RESPONSE - $countvote); $i++) {
                        if (isset($new_responses[$i])) {
                            $new_responses[$i] = trim(mb_substr($new_responses[$i], 0, 100));
                        } else {
                            $new_responses[$i] = '';
                        }
                    }
                    $tpl_data['input_new_responses'] = array_map('functions::checkout', $new_responses);
                }
            } else {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['error_wrong_data'];
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