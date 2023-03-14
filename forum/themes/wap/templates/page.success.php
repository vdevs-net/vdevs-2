<?php
    $this->layout('layout');
?>
<div class="gmenu">
    <p><?=$page_content?></p>
    <?php if (isset($back_url) && isset($back_text)): ?>
        <a href="<?=$back_url?>"><?=$back_text?></a>
    <?php endif ?>
</div>