<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="clearfix margin-bottom">
    <div class="pull-right"><a href="<?=$search_url?>" class="btn btn-primary btn-sm"><?=$lang['search']?></a><?php if ($forum_unread_count): ?><a href="<?=$forum_unread_url?>" class="btn btn-danger btn-sm margin-left"><?=$lang['unread']?> <span class="badge"><b><?=$forum_unread_count?></b></span></a><?php endif ?></div>
</div>
<?php if ($categories): ?>
    <div class="panel-group categories">
    <?php foreach ($categories as $cat): ?>
    <div class="panel panel-primary">
        <div class="panel-heading" id="<?=$cat['html_id']?>">
            <h3 class="cat-name panel-title"><a href="#<?=$cat['html_id']?>"><?=$cat['name']?></a></h3>
            <?php if ($cat['description']): ?><div class="sub"><?=$cat['description']?></div><?php endif ?>
        </div>
        <?php if ($cat['forums']): ?>
            <?php foreach ($cat['forums'] as $forum): ?>
            <div class="list-group-item">
                <h4 class="list-group-item-heading"><a href="<?=$forum['url']?>"><?=$forum['name']?></a></h4>
                <?php if ($forum['description']): ?><div class="list-group-item-text"><?=$forum['description']?></div><?php endif ?>
            </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="panel-body"><?=$lang['section_list_empty']?></div>
        <?php endif ?>
    </div>
    <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Diễn đàn chưa có chuyên mục nào! Nếu bạn là quản trị viên, vui lòng tạo chuyên mục diễn đàn trước.</div>
<?php endif ?>