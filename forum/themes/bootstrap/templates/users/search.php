<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <form action="search" method="get">
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="q" value="<?=$search?>" class="form-control" />
                <span class="input-group-btn">
                    <input type="submit" value="<?=$lang['search']?>" class="btn btn-default" />
                </span>
            </div>
        </div>
    </form>
    <?php if ($show_results): ?>
        <?php if ($total): ?>
            <div class="alert alert-info">Có <?=$total?> kết quả</div>
        <?php else: ?>
            <div class="alert alert-danger"><?=$lang['search_results_empty']?></div>
        <?php endif ?>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?=$error?></div>
        <?php endif ?>
    <?php endif ?>
</div>
<?php if ($show_results): ?>
    <?php if ($total): ?>
        <div class="list-group">
        <?php foreach ($items as $item): ?>
            <div class="list-group-item"><?=$item['content']?></div>
        <?php endforeach ?>
        </div>
    <?php endif ?>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>