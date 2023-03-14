<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="gmenu">
            <h3><?=$lang['voting']?></h3>
            <input type="text" size="20" maxlength="150" name="poll_question" value="<?=$poll_question?>"/>
    </div>
    <div class="menu">
        <h3><?=$lang['answer']?></h3>
        <ul>
        <?php foreach ($poll_responses as $response): ?>
            <li><input type="text" name="existing_response[<?=$response['id']?>]" value="<?=$response['text']?>"/><?php if ($response['delete_url']): ?>&nbsp;<a href="<?=$response['delete_url']?>">[x]</a><?php endif ?></li>
        <?php endforeach ?>
        <?php if ($input_new_responses): ?>
            <?php foreach ($input_new_responses as $new_response): ?>
                <li><input type="text" name="new_response[]" value="<?=$new_response?>"/></li>
            <?php endforeach ?>
        <?php endif ?>
        </ul>
    </div>
    <div class="gmenu">
        <p><input type="submit" name="submit" value="<?=$lang['save']?>" /></p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>