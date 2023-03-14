<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <form action="<?=$form_action?>" method="get">
        <div class="form-group">
            <div class="input-group">
                <input type="text" value="<?=$input_search?>" name="search" class="form-control" />
                <span class="input-group-btn">
                    <input type="submit" value="<?=$lang['search']?>" class="btn btn-default" />
                </span>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox"><label><input name="t" type="checkbox" value="1"<?=($input_search_t ? ' checked="checked"' : '')?> />&nbsp;<?=$lang['search_topic_name']?> </label></div>
        </div>
    </form>
    <?php if ($show_result): ?>
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
<?php if ($show_result): ?>
    <?php if ($total): ?>
        <div class="list-group">
        <?php foreach ($results as $item): ?>
            <div class="list-group-item">
                <h4><a href="<?=$item['thread_url']?>"><b><?=$item['thread_name']?></b></a></h4>
                <?php if ($item['tags']): ?>
                    <div><b>Tags</b>: <i><?=$item['tags']?></i></div>
                <?php endif ?>
                <div><b><?=$item['author']?></b> <span class="gray">(<?=$item['time']?>)</span></div>
                <div><?=$item['description']?></div>
            </div>
        <?php endforeach ?>
        </div>
    <?php endif ?>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
<?php endif ?>