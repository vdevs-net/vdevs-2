<?php
    $this->layout('layout');
?>
<?php if ($isCategoryList): ?>
    <div class="bmenu"><?=$lang['category_list']?></div>
<?php else: ?>
    <div class="bmenu"><a href="forum?mod=cat">&lt;&lt; <b><?=$categoryName?></b></a></div>
<?php endif ?>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu">
            <div><?php if ($isCategoryList): ?><a href="forum?mod=cat&id=<?=$item['id']?>"><?php endif ?><b><?=$item['name']?></b><?php if ($isCategoryList): ?></a> (<?=$item['childCount']?>)<?php endif ?> <a href="<?=$item['url']?>">&gt;&gt;</a></div>
            <?php if ($item['description']): ?><div class="gray"><?=$item['description']?></div><?php endif ?>
<?php if ($item['menu']) : ?>
            <div class="sub"><?php echo functions::displayMenu($item['menu'], '{item}', ' | '); ?></div>
<?php endif ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>
<div class="gmenu"><form action="<?=$addCatUrl?>" method="post"><input type="submit" value="<?=$lang['add']?>" /></form></div>