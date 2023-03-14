<?php
    $this->layout('layout');
?>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><a href="<?=$item['profile_url']?>" class="<?=$item['html_class']?>"><?=$item['name']?></a></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?></div>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>