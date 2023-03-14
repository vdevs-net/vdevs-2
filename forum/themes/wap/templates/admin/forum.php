<?php
    $this->layout('layout');
?>
<div class="gmenu">
    <p><h3><?=$lang['statistics']?></h3><ul>
        <li><?=$lang['categories']?>: <?=$total_cat?></li>
        <li><?=$lang['sections']?>: <?=$total_sub?></li>
        <li><?=$lang['themes']?>: <?=$total_thm?> / <span class="red"><?=$total_thm_del?></span></li>
        <li><?=$lang['messages']?>: <?=$total_msg?> / <span class="red"><?=$total_msg_del?></span></li>
        <li><?=$lang['files']?>: <?=$total_files?></li>
        <li><?=$lang['votes']?>: <?=$total_votes?></li>
    </ul></p>
</div>
<div class="menu">
    <p><h3><?=$lang['settings']?></h3><ul>
        <li><a href="forum?mod=cat"><b><?=$lang['forum_structure']?></b></a></li>
        <li><a href="forum?mod=files"><b><?=$lang['files']?></b> (<?=$total_files?>)</a></li>
        <li><a href="forum?mod=hposts"><?=$lang['hidden_posts']?></a> (<?=$total_msg_del?>)</li>
        <li><a href="forum?mod=htopics"><?=$lang['hidden_topics']?></a> (<?=$total_thm_del?>)</li>
    </ul></p>
</div>