<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="menu">
    <p><?=$whois_content?></p>
    <a href="<?=$back_url?>"><?=$lang['back']?></a>
</div>