<?php
    $this->layout('layout');
?>
<?php if ($error): ?>
    <div class="rmenu"><?=$error?></div>
<?php endif ?>
<form action="<?=$form_action?>" method="post" class="menu">
    <h3><?=$lang['voting']?></h3>
    <input type="text" size="20" maxlength="150" name="poll_question" value="<?=$input_question?>" />
    <h3><?=$lang['answer']?> (max. 100)</h3>
    <ul>
    <?php for ($i = 0; $i < MAX_POLL_RESPONSE; $i++): ?>
        <li><input type="text" name="poll_response[]" value="<?=$input_responses[$i]?>" placeholder="<?=$lang['answer']?>..." autocomplete="off" /></li>
    <?php endfor ?>
    </ul>
    <p><input type="submit" name="submit" value="<?=$lang['save']?>" /></p>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>