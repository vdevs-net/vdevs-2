<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($menu): ?>
    <div class="panel-group">
    <?php foreach ($menu as $group): ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><?=$group['name']?></div>
            <?php if ($group['items']): ?>
                <div class="list-group">
                <?php foreach ($group['items'] as $item): ?>
                    <div class="list-group-item"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
                <?php endforeach ?>
                </div>
            <?php else: ?>
                <div class="panel-body"><?=$lang['list_empty']?></div>
            <?php endif ?>
        </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="panel panel-primary">
        <div class="panel-body"><?=$lang['list_empty']?></div>
    </div>
<?php endif ?>