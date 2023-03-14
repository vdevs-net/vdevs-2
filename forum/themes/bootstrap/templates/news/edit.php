<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <form action="<?=$form_action?>" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="name" class="form-label col-sm-3"><?=$lang['article_title']?></label>
            <div class="col-sm-9">
                <input type="text" name="name" value="<?=$news_title?>" class="form-control" id="name" />
            </div>
        </div>
        <div class="form-group">
            <label for="content" class="form-label col-sm-3"><?=$lang['text']?></label>
            <div class="col-sm-9">
                <textarea rows="10" name="text" class="form-control" id="content"><?=$news_content?></textarea>
            </div>
        </div>
        <hr />
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3"><input type="submit" name="submit" value="<?=$lang['save']?>" class="btn btn-primary" /></div>
        </div>
    </form>
</div>
</div>