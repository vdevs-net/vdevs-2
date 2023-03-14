<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);

$lng = array_merge($lng, core::load_lng('ban'));
switch ($mod) {
    case 'amnesty':
        $breadcrumb->add('/admin/ban-panel', $lng['ban_panel']);
        $breadcrumb->add($lng['amnesty']);
        if ($rights < 9) {
            $error_rights = true;
        } else {
            if (IS_POST) {
                $term = isset($_POST['term']) ? abs(intval($_POST['term'])) : 0;
                if ($term) {
                    // Clear the table Ban
                    mysql_query('TRUNCATE TABLE `cms_ban_users`');
                    $tpl_data['page_content'] = $lng['amnesty_clean_confirm'];
                } else {
                    // Unban user
                    mysql_query('UPDATE `cms_ban_users` SET `ban_time` = "' . SYSTEM_TIME . '" WHERE (`ban_time` - ' . SYSTEM_TIME . ' > 0) AND (`ban_time` - ' . SYSTEM_TIME . ' < 2592000)');
                    $tpl_data['page_content'] = $lng['amnesty_delban_confirm'];
                }
                $tpl_file = 'page.success';
            } else {
                $tpl_file = 'admin::ban-panel.amnesty';
            }
        }
        break;

    default:
        $breadcrumb->add($lng['ban_panel']);
        $tpl_file = 'admin::ban-panel';
        $tpl_data['tabs'] = [
            [
                'url' => 'ban-panel',
                'name' => $lng['term'],
                'active' => !isset($_GET['count'])
            ],
            [
                'url' => 'ban-panel?count',
                'name' => $lng['infringements'],
                'active' => isset($_GET['count'])
            ]
        ];

        $sort = isset($_GET['count']) ? 'bancount' : 'bantime';
        $total = mysql_result(mysql_query('SELECT COUNT(DISTINCT `user_id`) FROM `cms_ban_users`'), 0);
        $tpl_data['total'] = $total;
        $tpl_data['pagination'] = '';
        if ($total) {
            $start = functions::fixStart($start, $total, $kmess);
            $req = mysql_query('SELECT COUNT(`cms_ban_users`.`user_id`) AS `bancount`, MAX(`cms_ban_users`.`ban_time`) AS `bantime`, `users`.*
            FROM `cms_ban_users`
            LEFT JOIN `users` ON `cms_ban_users`.`user_id` = `users`.`id`
            GROUP BY `cms_ban_users`.`user_id`
            ORDER BY `' . $sort . '` DESC
            LIMIT ' . $start . ', ' . $kmess) or die(mysql_error());
            while ($res = mysql_fetch_assoc($req)) {
                $arg = array (
                    'header' => '<img src="' . SITE_URL . '/assets/images/block.gif" width="16" height="16" align="middle" />&#160;<small><a href="' . SITE_URL . '/profile/' . $res['account'] . '.' . $res['id'] . '/ban">' . $lng['infringements_history'] . '</a> [' . $res['bancount'] . ']</small>'
                );
                $tpl_data['items'][] = [
                    'html_class' => ($res['bantime'] > SYSTEM_TIME ? 'r' : '') . 'menu',
                    'content' => functions::display_user($res, $arg)
                ];
            }
        }
        if ($total > $kmess) {
            $tpl_data['pagination'] = functions::display_pagination('ban-panel?page=', $start, $total, $kmess);
        }
}

$_breadcrumb = $breadcrumb->out();
