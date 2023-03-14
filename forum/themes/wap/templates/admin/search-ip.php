<?php
    $this->layout('layout');
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<form action="search-ip" method="get"><div class="gmenu">
    <p>
        <input type="text" name="ip" value="<?=$search_keyword?>" />
        <input type="submit" value="<?=$lang['search']?>" />
    </p>
    <?=$hiddenInput?>
</div></form>
<?php if ($show_result): ?>
    <div class="phdr"><?=$lang['search_results']?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <?php if ($total): ?>
        <?php foreach ($items as $item): ?>
            <div class="menu"><?=$item['content']?></div>
        <?php endforeach ?>
    <?php else: ?>
        <div class="menu"><p><?=$lang['not_found']?></p></div>
    <?php endif ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <div class="menu"><a href="search-ip"><?=$lang['search_new']?></a></div>
<?php else: ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <div class="notif"><?=$lang['search_ip_help']?></div>
<?php endif ?>