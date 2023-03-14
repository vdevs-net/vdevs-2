<?php
    $this->layout('layout');
?>
<form action="<?=$form_action?>" method="post">
    <?php if ($error): ?><div class="rmenu"><?=$error?></div><?php endif ?>
    <div class="menu">
        <p>
            <h3><?=$lang['article_title']?></h3>
            <input type="text" name="name" autocomplete="off" value="<?=$input_title?>" />
        </p>
        <p>
            <h3><?=$lang['text']?></h3>
            <textarea rows="<?=$user['field_h']?>" name="text"><?=$input_content?></textarea>
        </p>
        <p>
            <h3><?=$lang['discuss']?></h3>
            <select name="forum_id">
                <option value="0"><?=$lang['discuss_off']?></option>
                <?php foreach ($categories as $cat): ?>
                    <optgroup label="<?=$cat['name']?>">
                    <?php foreach ($cat['items'] as $item): ?>
                        <option<?php if ($item['id'] == $input_forum_id): ?> selected="selected"<?php endif ?> value="<?=$item['id']?>"><?=$item['name']?></option>
                    <?php endforeach ?>
                    </optgroup>
                <?php endforeach ?>
            </select>
        </p>
    </div>
    <div class="bmenu"><input type="submit" name="submit" value="<?=$lang['save']?>"/></div>
    <input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
</form>