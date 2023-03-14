<?php
    $this->layout('layout');
?>
<?php if ($total): ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <div class="topmenu">
        <form action="<?=$form_action?>" method="post" name="chat" id="chat">
            <div><textarea name="text" id="chat_input" rows="<?=$user['field_h']?>" required="required"></textarea></div>
            <div><input type="submit" name="submit" value="Gửi" id="chat_submit"></div>
            <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
        </form>
    </div>
    <div id="chatbox">
    <?php foreach ($messages as $message): ?>
        <div class="menu"><b><a href="<?=$message['user_profile_url']?>" title="<?=$message['time']?>" class="<?=$message['user_html_class']?>"><?=$message['user_name']?></a></b>: <?=$message['text']?></div>
    <?php endforeach ?>
    </div>
    <?php if ($pagination): ?>
        <div class="topmenu"><?=$pagination?></div>
    <?php endif ?>
<?php endif ?>