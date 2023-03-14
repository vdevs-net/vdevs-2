<?php
defined('_IS_MRKEN') or die('Error: restricted access');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/admin/', $lng['admin_panel']);
$breadcrumb->add('IP WHOIS');
$_breadcrumb = $breadcrumb->out();

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : false;
if ($ip) {
    $ipwhois = '';
    if (($fsk = @fsockopen('whois.arin.net.', 43))) {
        fputs($fsk, "$ip\r\n");
        while (!feof($fsk)) $ipwhois .= fgets($fsk, 1024);
        @fclose($fsk);
    }
    $match = array();
    if (preg_match('#ReferralServer: whois://(.+)#im', $ipwhois, $match)) {
        if (strpos($match[1], ':') !== false) {
            $pos = strrpos($match[1], ':');
            $server = substr($match[1], 0, $pos);
            $port = (int)substr($match[1], $pos + 1);
            unset($pos);
        } else {
            $server = $match[1];
            $port = 43;
        }
        $buffer = '';
        if (($fsk = @fsockopen($server, $port))) {
            fputs($fsk, "$ip\r\n");
            while (!feof($fsk)) $buffer .= fgets($fsk, 1024);
            @fclose($fsk);
        }
        $ipwhois = (empty($buffer)) ? $ipwhois : $buffer;
    }
    $array = array(
        '%' => '#',
        'inetnum:' => '<strong class="red">inetnum:</strong>',
        'netname:' => '<strong class="green">netname:</strong>',
        'descr:' => '<strong class="red">descr:</strong>',
        'country:' => '<strong class="red">country:</strong>',
        'admin-c:' => '<strong class="gray">admin-c:</strong>',
        'tech-c:' => '<strong class="gray">tech-c:</strong>',
        'status:' => '<strong class="gray">status:</strong>',
        'mnt-by:' => '<strong class="gray">mnt-by:</strong>',
        'mnt-lower:' => '<strong class="gray">mnt-lower:</strong>',
        'mnt-routes:' => '<strong class="gray">mnt-routes:</strong>',
        'source:' => '<strong class="gray">source:</strong>',
        'role:' => '<strong class="gray">role:</strong>',
        'address:' => '<strong class="green">address:</strong>',
        'e-mail:' => '<strong class="green">e-mail:</strong>',
        'nic-hdl:' => '<strong class="gray">nic-hdl:</strong>',
        'org:' => '<strong class="gray">org:</strong>',
        'person:' => '<strong class="green">person:</strong>',
        'phone:' => '<strong class="green">phone:</strong>',
        'remarks:' => '<strong class="gray">remarks:</strong>',
        'route:' => '<strong class="red"><b>route:</b></strong>',
        'origin:' => '<strong class="gray">origin:</strong>',
        'organisation:' => '<strong class="gray">organisation:</strong>',
        'org-name:' => '<strong class="red"><b>org-name:</b></strong>',
        'org-type:' => '<strong class="gray">org-type:</strong>',
        'abuse-mailbox:' => '<strong class="red"><b>abuse-mailbox:</b></strong>',
        'mnt-ref:' => '<strong class="gray">mnt-ref:</strong>',
        'fax-no:' => '<strong class="green">fax-no:</strong>',
        'NetType:' => '<strong class="gray">NetType:</strong>',
        'Comment:' => '<strong class="gray">Comment:</strong>'
    );
    $ipwhois = trim(bbcode::highlight_url(htmlspecialchars($ipwhois)));
    $ipwhois = strtr($ipwhois, $array);
} else {
    $ipwhois = $lng['error_wrong_data'];
}
$tpl_file = 'admin::ip-whois';
$tpl_data['whois_content'] = nl2br($ipwhois);
$tpl_data['back_url'] = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']):  SITE_URL;
