<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php $this->insert('profile::cover', $profileCoverVariable); ?>
<?php if ($is_birthday): ?>
    <div class="alert alert-info"><?=$lang['birthday']?></div>
<?php endif ?>
<?php if ($not_activated): ?>
    <div class="alert alert-danger"><?=$lang['awaiting_registration']?></div>
<?php endif ?>
<div id="profileForm"></div>
<form action="<?=$form_action?>" method="post" name="form">
<div class="panel panel-primary margin-bottom">
<div class="panel-body">
    <div class="form-group">
    <?=$bbcode_editor?>
    <textarea name="text" class="form-control" placeholder="Bạn đang nghĩ gì?" rows="2" required="required"></textarea>
    </div>
    <input type="hidden" name="token" value="<?=$token?>" />
</div>
<div class="panel-footer clearfix"><div class="pull-left"><select name="privacy" class="form-control"><option value="0">Mọi người</option><option value="2">Chỉ mình tôi</option></select></div><div class="pull-right"><input type="submit" name="submit" value="<?=$lang['write']?>" class="btn btn-primary" /></div></div>
</div>
</form>
<div class="panel panel-primary">
<?php if ($posts): ?>
    <div class="list-group">
    <?php foreach ($posts as $post): ?>
        <div class="list-group-item profilePost">
            <table width="100%"><tr>
                <td width="36px"><img src="<?=$post['user_avatar']?>" width="32" height="32" alt="<?=$post['user_name']?>"></td>
                <td><?php if ($post['edit_url'] || $post['delete_url']): ?><div class="pull-right dropdown statusControl"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-chevron-down"></i></a><ul class="dropdown-menu"><?php if ($post['edit_url']): ?><li><a href="<?=$post['edit_url']?>"><?=$lang['edit']?></a></li><?php endif ?><?php if ($post['delete_url']): ?><li><a href="<?=$post['delete_url']?>"><?=$lang['delete']?></a></li><?php endif ?></ul></div><?php endif ?><div><a href="<?=$post['user_profile_url']?>" class="<?=$post['user_html_class']?>"><?=$post['user_name']?></a></div><div class="small"><?=$post['time']?> &middot; <?=$post['privacy']?></div></td>
            </tr></table>
            <div class="profilePostText"><?=$post['text']?></div>
        </div>
    <?php endforeach ?>
    </div>
<?php endif ?>
</div>
<?php if ($pagination): ?>
    <div class="clearfix margin-top"><div class="pull-right paging"><?=$pagination?></div></div>
 <?php endif ?>