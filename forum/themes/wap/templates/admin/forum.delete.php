<?php
    $this->layout('layout');
?>
<div class="rmenu"><?=$warningText?></div>
<form action="<?=$formAction?>" method="post">
<div class="menu">
    <h3><?php if ($isCategory): ?><?=$lang['select_category']?><?php else: ?><?=$lang['select_section']?><?php endif ?></h3>
    <?php if ($destinations): ?>
        <?php foreach ($destinations as $destination): ?>
            <div><input type="radio" name="<?=$destinationName?>" value="<?=$destination['id']?>" /> <?=$destination['name']?></div>
        <?php endforeach ?>
    <?php else: ?>
        <div><?=$lang['list_empty']?></div>
    <?php endif ?>
</div>
<?php if ($otherCategories): ?>
<div class="menu">
    <h3><?=$lang['another_category']?></h3>
    <ul>
    <?php foreach ($otherCategories as $category): ?>
        <li><a href="<?=$category['url']?>"><?=$category['name']?></a></li>
    <?php endforeach ?>
    </ul>
</div>
<?php endif ?>
<div class="rmenu"><?=$descriptionText?></div>
<div class="menu"><input type="submit" name="submit" value="<?=$lang['move']?>" /></div>
<?php if ($rights == RIGHTS_SUPER_ADMIN): ?>
    <?php if ($isCategory): ?>
        <div class="rmenu"><p><h3><?=$lang['delete_full']?></h3><?=$lang['delete_full_note']?></a></p></div>
    <?php else: ?>
        <div class="rmenu">
            <p><h3><?=$lang['delete_full']?></h3><?=$lang['delete_full_warning']?></p>
            <p><input type="submit" name="delete" value="<?=$lang['delete']?>" /></p>
        </div>
    <?php endif ?>
<?php endif ?>
</form>