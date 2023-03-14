<?php
    $this->layout('layout');
?>

<?php if ($total): ?>
    <?php foreach ($items as $item): ?>
        <div class="<?php echo ($item['premod'] ? '' : 'r'); ?>menu">
            <div class="righttable">
                <h4><a href="<?=$item['editUrl']?>"><?=$item['name']?></a></h4>
                <div><small><?=$item['announce']?></small></div>
            </div>
            <table class="desc">
                <tr><td class="caption"><?=$lang['tags']?>: </td><td><?php if ($item['tags']): ?><?=$item['tags']?><?php else: ?>--<?php endif ?></td></tr>
                <tr><td class="caption"><?=$lang['added']?>: </td><td><a href="<?=$item['authorUrl']?>"><?=$item['author']?></a> (<?=$item['time']?>)</td>
                </tr><tr><td class="caption"><?=$lang['comments']?>: </td><td><?php if ($item['commentCount']): ?><a href="<?=$item['commentUrl']?>"><?=$item['commentCount']?></a><?php else: ?>--<?php endif ?></td></tr>
                <tr><td class="caption"><?=$lang['rating']?>: </td><td>' . $rate->view_rate() . '</td></tr>
            </table>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="menu"><?=$lang['list_empty']?></div>
<?php endif ?>
<?php if ($pagination): ?>
    <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
    <div class="topmenu"><?=$pagination?></div>
<?php endif ?>
