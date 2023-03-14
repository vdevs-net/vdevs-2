<?php
    $this->layout('layout');
?>
<?php if ($can_write): ?>
<div class="gmenu">
    <form name="form" action="<?=$form_action?>" method="post"  enctype="multipart/form-data">
        <p><textarea rows="<?=$user['field_h']?>" name="text"></textarea></p>
        <p><input type="file" name="fail" style="width: 100%; max-width: 160px"/></p>
        <p><input type="submit" name="submit" value="<?=$lang['sent']?>"/></p>
    </form>
</div>
<?php endif ?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="<?=$item['html_class']?>"><?=$item['content']?></div>
    <?php endforeach ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
    <div class="menu"><a href="<?=$clear_url?>"><?=$lang['clear_messages']?></a></div>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>