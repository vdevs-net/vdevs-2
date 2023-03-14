<?php
    $this->layout('layout');
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <p><img src="<?=SITE_URL?>/assets/images/<?php if ($item['running']): ?>green<?php else: ?>red<?php endif ?>.gif" width="16" height="16" class="left" /> <a href="<?=$item['link']?>"><?=$item['link']?></a> [<?=$item['count']?>]<br /><?=$item['name']?></p>
            <div class="sub">
                <div><?=functions::display_menu($item['menu'])?></div>
                <div>
                    <p><span class="gray"><?=$lang['installation_date']?>:</span> <?=$item['installation_date']?></p>
                    <p><span class="gray"><?=$lang['placing']?>:</span> <?=$item['placing']?></p>
                    <p><span class="gray"><?=$lang['to_show']?>:</span> <?=$item['to_show']?></p>
                    <?php if ($item['agreement']): ?>
                        <p><span class="gray"><?=$lang['agreement']?>:</span> <?=implode($item['agreement'], ', ')?></p>
                        <?php if ($item['remains']): ?>
                            <p><span class="gray"><?=$lang['remains']?>:</span> <?=implode($item['remains'], ', ')?></p>
                        <?php endif ?>
                    <?php endif ?>
                    <?php if ($item['show']): ?>
                        <p><span class="red"><b><?=$lang['link_direct']?></b></span></p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<div class="phdr"><?=$lang['total']?>: <?=$total?></div>
<?php if ($pagination): ?>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
<div class="menu"><a href="ads?mod=edit"><?=$lang['link_add']?></a></div>
<div class="menu"><a href="ads?mod=clear"><?=$lang['links_delete_hidden']?></a></div>