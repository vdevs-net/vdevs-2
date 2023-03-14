<?php
    $this->layout('layout');
?>
<div class="topmenu"><?=$this->display_tab($tabs)?></div>
<?php if ($items): ?>
    <?php foreach ($items as $item): ?>
        <div class="menu"><?=$item['content']?></div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>