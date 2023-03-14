<?php
defined('_IS_MRKEN') or die('Error: restricted access');
define('ROOT_DIR', '.');

// Check right
if ($rights < 7) {
    $error_rights = true;
} else {
class scaner {
    // Antispyware scanner
    public $scan_folders = array (
        '',
        '/admin',
        '/api',
        '/assets',
        '/files',
        '/modules',
        '/rss',
        '/system',
        '/themes'
    );
    public $good_files = array (
        './.htaccess',
        './assets/captcha.php',
        './assets/re_captcha.php',
        './go.php',
        './index.php',
        './login.php',
        './registration.php',
        './files/.htaccess',
        './files/system/cache/.htaccess',
        './files/forum/attach/index.php',
        './files/forum/index.php',
        './files/forum/topics/index.php',
        './files/messages/index.php',
        './files/users/avatar/index.php',
        './files/users/index.php',
        './files/users/photo/index.php',
        './forum/includes/addfile.php',
        './forum/includes/addvote.php',
        './forum/includes/close.php',
        './forum/includes/curators.php',
        './forum/includes/deltema.php',
        './forum/includes/delvote.php',
        './forum/includes/editpost.php',
        './forum/includes/editvote.php',
        './forum/includes/file.php',
        './forum/includes/files.php',
        './forum/includes/move.php',
        './forum/includes/new.php',
        './forum/includes/nt.php',
        './forum/includes/quote.php',
        './forum/includes/post.php',
        './forum/includes/ren.php',
        './forum/includes/restore.php',
        './forum/includes/reply.php',
        './forum/includes/users.php',
        './forum/includes/vip.php',
        './forum/includes/vote.php',
        './forum/includes/who.php',
        './forum/contents.php',
        './forum/index.php',
        './forum/search.php',
        './forum/thumbinal.php',
        './images/avatars/index.php',
        './images/captcha/.htaccess',
        './images/index.php',
        './images/smileys/index.php',
        './images/smileys/simply/index.php',
        './images/smileys/user/index.php',
        './system/.htaccess',
        './system/classes/bbcode.php',
        './system/classes/CleanUser.php',
        './system/classes/comments.php',
        './system/classes/core.php',
        './system/classes/counters.php',
        './system/classes/functions.php',
        './system/classes/mainpage.php',
        './system/core.php',
        './system/db.php',
        './system/func.php',
        './system/header.php',
        './system/index.php',
        './messages/includes/delete.php',
        './messages/includes/deluser.php',
        './messages/includes/files.php',
        './messages/includes/ignor.php',
        './messages/includes/input.php',
        './messages/includes/load.php',
        './messages/includes/new.php',
        './messages/includes/output.php',
        './messages/includes/systems.php',
        './messages/includes/write.php',
        './messages/index.php',
        './admin/includes/ads.php',
        './admin/includes/access.php',
        './admin/includes/antiflood.php',
        './admin/includes/antispy.php',
        './admin/includes/ban_panel.php',
        './admin/includes/forum.php',
        './admin/includes/ipban.php',
        './admin/includes/ip_whois.php',
        './admin/includes/languages.php',
        './admin/includes/mail.php',
        './admin/includes/reg.php',
        './admin/includes/search_ip.php',
        './admin/includes/settings.php',
        './admin/includes/smileys.php',
        './admin/includes/usr.php',
        './admin/includes/usr_adm.php',
        './admin/includes/usr_clean.php',
        './admin/includes/usr_del.php',
        './admin/index.php',
        './pages/faq.php',
        './pages/index.php',
        './rss/rss.php',
        './users/includes/admlist.php',
        './users/includes/birth.php',
        './users/includes/online.php',
        './users/includes/profile/activity.php',
        './users/includes/profile/ban.php',
        './users/includes/profile/edit.php',
        './users/includes/profile/friends.php',
        './users/includes/profile/images.php',
        './users/includes/profile/info.php',
        './users/includes/profile/ip.php',
        './users/includes/profile/office.php',
        './users/includes/profile/password.php',
        './users/includes/profile/reset.php',
        './users/includes/profile/settings.php',
        './users/search.php',
        './users/includes/top.php',
        './users/includes/userlist.php',
        './users/index.php',
        './users/profile.php',
        './users/skl.php'
    );
    public $snap_base = 'scan_snapshot.dat';
    public $snap_files = array ();
    public $bad_files = array ();
    public $snap = false;
    public $track_files = array ();
    private $checked_folders = array ();
    private $cache_files = array ();
    function scan() {
        // Scan to the appropriate distribution
        foreach ($this->scan_folders as $data) {
            $this->scan_files(ROOT_DIR . $data);
        }
    }
    function snapscan() {
        // Scan the image
        if (file_exists(ROOTPATH . 'files/system/cache/' . $this->snap_base)) {
            $filecontents = file(ROOTPATH . 'files/system/cache/' . $this->snap_base);
            foreach ($filecontents as $name => $value) {
                $filecontents[$name] = explode('|', trim($value));
                $this->track_files[$filecontents[$name][0]] = $filecontents[$name][1];
            }
            $this->snap = true;
        }

        foreach ($this->scan_folders as $data) {
            $this->scan_files(ROOT_DIR . $data);
        }
    }
    function snap() {
        // Adding picture files in a secure database
        foreach ($this->scan_folders as $data) {
            $this->scan_files(ROOT_DIR . $data, true);
        }
        $filecontents = "";

        foreach ($this->snap_files as $idx => $data) {
            $filecontents .= $data['file_path'] . '|' . $data['file_crc'] . "\r\n";
        }
        $filehandle = fopen(ROOTPATH . 'files/system/cache/' . $this->snap_base, "w+");
        fwrite($filehandle, $filecontents);
        fclose($filehandle);
        @chmod(ROOTPATH . 'files/system/cache/' . $this->snap_base, 0666);
    }
    function scan_files($dir, $snap = false) {
        // A utility function scan
        if (!isset($file)) {
            $file = false;
        }
        $this->checked_folders[] = $dir . '/' . $file;

        if ($dh = @opendir($dir)) {
            while (false !== ($file = readdir($dh))) {
                if ($file == '.' or $file == '..' or $file == '.svn' or $file == '.DS_store') {
                    continue;
                }
                if (is_dir($dir . '/' . $file)) {
                    if ($dir != ROOT_DIR) {
                        $this->scan_files($dir . '/' . $file, $snap);
                    }
                } else {
                    if ($this->snap or $snap) {
                        $templates = '|tpl';
                    } else {
                        $templates = '';
                    }
                    if (preg_match("#.*\.(php|cgi|pl|perl|php3|php4|php5|php6|phtml|py|htaccess" . $templates . ")$#i", $file)) {
                        $folder = str_replace("../..", ".", $dir);
                        $file_size = filesize($dir . '/' . $file);
                        $file_crc = strtoupper(dechex(crc32(file_get_contents($dir . '/' . $file))));
                        $file_date = date("d.m.Y H:i:s", filectime($dir . '/' . $file));
                        if ($snap) {
                            $this->snap_files[] = array (
                                'file_path' => $folder . '/' . $file,
                                'file_crc' => $file_crc
                            );
                        } else {
                            if ($this->snap) {
                                if (!in_array($folder . '/' . $file, $this->cache_files) and (!isset($this->track_files[$folder . '/' . $file]) || $this->track_files[$folder . '/' . $file] != $file_crc)) {
                                    $this->bad_files[] = array (
                                        'file_path' => $folder . '/' . $file,
                                        'file_name' => $file,
                                        'file_date' => $file_date,
                                        'type' => 1,
                                        'file_size' => $file_size
                                    );
                                }
                            } else {
                                if (!in_array($folder . '/' . $file, $this->good_files) or $file_size > 300000)
                                    $this->bad_files[] = array (
                                        'file_path' => $folder . '/' . $file,
                                        'file_name' => $file,
                                        'file_date' => $file_date,
                                        'type' => 0,
                                        'file_size' => $file_size
                                    );
                            }
                        }
                    }
                }
            }
            closedir($dh);
        }
    }
}
    
    $breadcrumb = new breadcrumb();
    $breadcrumb->add('/admin/', $lng['admin_panel']);

    $scaner = new scaner();
    switch ($mod) {
        case 'scan':
            $breadcrumb->add('/admin/antispy', $lng['antispy']);
            $breadcrumb->add($lng['antispy_dist_scan']);
            // Scan for compliance distro
            $scaner->scan();
            if (count($scaner->bad_files)) {
                $tpl_file = 'admin::antispy.scan';
                $tpl_data['alert_text'] = $lng['antispy_dist_scan_bad'];
                $tpl_data['bad_files'] = $scaner->bad_files;
                $tpl_data['rescan_url'] = 'antispy?mod=scan';
            } else {
                $tpl_file = 'page.success';
                $tpl_data['page_content'] = $lng['antispy_dist_scan_good'];
            }
            break;

        case 'snapscan':
            $breadcrumb->add('/admin/antispy', $lng['antispy']);
            $breadcrumb->add($lng['antispy_snapshot_scan']);
            // Scan for compliance with the previously created snapshot
            $scaner->snapscan();
            if (count($scaner->track_files) == 0) {
                $tpl_file = 'page.error';
                $tpl_data['page_content'] = $lng['antispy_no_snapshot'];
                $tpl_data['back_url'] = 'antispy?mod=snap';
                $tpl_data['nback_text'] = $lng['antispy_snapshot_create'];
            } else {
                if (count($scaner->bad_files)) {
                    $tpl_file = 'admin::antispy.scan';
                    $tpl_data['alert_text'] = $lng['antispy_snapshot_scan_bad'];
                    $tpl_data['bad_files'] = $scaner->bad_files;
                    $tpl_data['rescan_url'] = 'antispy?mod=snapscan';
                } else {
                    $tpl_file = 'page.success';
                    $tpl_data['page_content'] = $lng['antispy_snapshot_scan_ok'];
                }
            }
            break;

        case 'snap':
            $breadcrumb->add('/admin/antispy', $lng['antispy']);
            $breadcrumb->add($lng['antispy_snapshot_create']);
            // Create a picture file
            if (IS_POST) {
                $scaner->snap();
                $tpl_file = 'page.success';
                $tpl_data['page_content'] = $lng['antispy_snapshot_create_ok'];
            } else {
                $tpl_file = 'page.confirm';
                $tpl_data['form_action'] = 'antispy?mod=snap';
                $tpl_data['confirm_text'] = $lng['antispy_snapshot_warning'];
                $tpl_data['cancel_url'] = 'antispy';
            }
            break;

        default:
            $breadcrumb->add($lng['antispy']);
            $tpl_file = 'admin::antispy';
    }
    $_breadcrumb = $breadcrumb->out();
}
