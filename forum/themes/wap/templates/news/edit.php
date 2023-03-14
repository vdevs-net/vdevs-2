<?php
    $this->layout('layout');
?>
<div class="menu">
    <form action="<?=$form_action?>" method="post">
        <p>
            <h3><?=$lang['article_title']?></h3>
            <input type="text" name="name" value="<?=$news_title?>" />
        </p>
        <p>
            <h3><?=$lang['text']?></h3>
            <textarea rows="<?=$user['field_h']?>" name="text"><?=$news_content?></textarea>
        </p>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>"/></p>
    </form>
</div>