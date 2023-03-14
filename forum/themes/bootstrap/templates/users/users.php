<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
<div class="panel-body">
    <form action="<?=$search_user_form_action?>" method="get">
        <div class="form-group">
            <div class="row">
            <div class="col-sm-9 col-xs-6">
                <input type="text" name="q" class="form-control" id="keyword" />
            </div>
            <div class="col-sm-3 col-xs-6">
                <input type="submit" value="<?=$lang['search']?>" class="btn btn-primary" />
            </div>
            </div>
        </div>
    </form>
</div>
<div class="list-group">
<div class="list-group-item"><a href="userlist"><?=$lang['users']?></a> (<?=$count_users?>)</div>
<div class="list-group-item"><a href="userlist?type=staff"><?=$lang['administration']?></a> (<?=$count_admin?>)</div>
<?php if ($count_birth): ?>
    <div class="list-group-item"><a href="userlist?type=birthday"><?=$lang['birthday_men']?></a> (<?=$count_birth?>)</div>
<?php endif ?>
<div class="list-group-item"><a href="top"><?=$lang['users_top']?></a></div>
</div>
</div>