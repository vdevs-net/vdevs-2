<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
    <?php if ($pagination): ?>
        <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
        <div class="topmenu"><?=$pagination?></div>
        <div class="menu"><form action="userlist" method="get"><?=$hidden_input?><input type="text" name="page" size="2" value="<?=$current_page?>" /><input type="submit" value="<?=$lang['to_page']?> &gt;&gt;"/></form></div>
    <?php endif ?>
<?php else: ?>
    <div class="menu"><p><?=$lang['list_empty']?></p></div>
<?php endif ?>