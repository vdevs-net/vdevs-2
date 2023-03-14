<?php
    $this->layout('layout');
?>
<?php if ($menu): ?>
    <?php foreach ($menu as $group): ?>
        <div class="box box-menu">
            <h4 class="box-header"><?=$group['name']?></h4>
            <?php if ($group['items']): ?>
                <div class="box-body">
                <?php foreach ($group['items'] as $item): ?>
                    <div class="menu"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
                <?php endforeach ?>
                </div>
            <?php else: ?>
                <?=$lang['list_empty']?>
            <?php endif ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <?=$lang['list_empty']?>
<?php endif ?>