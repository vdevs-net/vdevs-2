<?php
    $this->layout('layout');
?>
<?php if ($parentUrl): ?>
    <div class="bmenu"><a href="<?=$parentUrl?>">&lt;&lt; <b><?=$parentName?></b></a></div>
<?php else: ?>
    <div class="bmenu">Danh sách chuyên mục</div>
<?php endif ?>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <div><?php if ($item['isSection']): ?><a href="<?=$item['url']?>"><?php endif ?><b><?=$item['name']?></b><?php if ($item['isSection']): ?></a> (<?=$item['childCount']?>)<?php endif ?></div>
            <?php if ($item['description']): ?><div class="gray"><?=$item['description']?></div><?php endif ?>
<?php if ($item['menu']) : ?>
            <div class="sub"><?php echo functions::displayMenu($item['menu'], '{item}', ' | '); ?></div>
<?php endif ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<div class="gmenu"><form action="<?=$addCatUrl?>" method="post"><input type="submit" value="<?=$lang['create_category']?>" /></form></div>