<?php
    $this->layout('layout');
?>
<form action="search" method="get">
    <div class="gmenu">
        <p><input type="text" name="q" value="<?=$search?>" /> <input type="submit" value="<?=$lang['search']?>" /></p>
    </div>
</form>
<?php if ($show_results): ?>
    <div class="phdr"><b><?=$lang['search_results']?></b></div>
    <?php if ($total): ?>
        <?php if ($pagination): ?>
            <div class="topmenu"><?=$pagination?></div>
        <?php endif ?>
        <?php foreach ($items as $item): ?>
            <div class="menu"><?=$item['content']?></div>
        <?php endforeach ?>
        <?php if ($pagination): ?>
            <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
            <div class="topmenu"><?=$pagination?></div>
        <?php endif ?>
    <?php else: ?>
        <div class="menu"><p><?=$lang['search_results_empty']?></p></div>
    <?php endif ?>
<?php else: ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <div class="phdr"><small><?=$lang['search_nick_help']?></small></div>
<?php endif ?>