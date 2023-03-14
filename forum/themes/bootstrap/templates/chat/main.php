<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-primary">
    <div class="panel-body">
        <form action="<?=$form_action?>" method="post" name="chat" id="chat" class="form-horizontal noPusher">
            <div class="row">
                <div class="col-xs-9"><textarea name="text" id="chat_input" rows="<?=$user['field_h']?>" required="required" class="form-control" ></textarea></div>
                <div class="col-xs-3"><input type="submit" name="submit" value="Gửi" id="chat_submit" class="btn btn-primary btn-block" /></div>
            </div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        </form>
    <?php if ($error): ?>
        <div class="alert alert-danger margin-top"><?=$error?></div>
    <?php endif ?>
    </div>
    <div id="chatbox" class="list-group">
<?php if ($total): ?>
    <?php foreach ($messages as $message): ?>
        <div class="list-group-item" data-time="<?=$message['data_time']?>" data-id="<?=$message['data_id']?>"><?php if ($message['data_id']): ?><a href="#" class="confirm noPusher"><i class="fa fa-trash fa-fw"></i></a> <?php endif ?><a href="<?=$message['user_profile_url']?>" title="<?=$message['time']?>" class="<?=$message['user_html_class']?> chatUser noPusher"><?=$message['user_name']?></a>: <?=$message['text']?></div>
    <?php endforeach ?>
<?php endif ?>
    </div>
</div>
<?php if ($pagination): ?>
    <button class="btn btn-primary margin-bottom btn-block" id="chatLoadMore">Load more</button>
<?php endif ?>
