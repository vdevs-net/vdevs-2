<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <p><?=$confirm_text?></p>
        <?php if (isset($confirm_warning)): ?>
            </div><div class="notif"><?=$confirm_warning?></div><div class="menu">
        <?php endif ?>
        <?php if (isset($confirm_options)): ?>
            <?php foreach ($confirm_options as $group): ?>
                <p>
                    <h3><?=$group['title']?></h3>
                    <?php foreach ($group['items'] as $item): ?>
                        <label><input type="<?=$item['type']?>" name="<?=$item['name']?>" value="<?=$item['value']?>" /> <?=$item['explain']?></label>
                    <?php endforeach ?>
                </p>
            <?php endforeach ?>
        <?php endif ?>
        <p>
            <input type="submit" name="submit" value="<?=$lang['yes']?>" />
            <a href="<?=$cancel_url?>" class="btn cancel"><?=$lang['no']?></a>
        </p>
    </div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>