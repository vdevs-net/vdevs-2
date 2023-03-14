<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="panel panel-success"><div class="panel-body bg-success">
    <p><?=$page_content?></p>
    <?php if (isset($back_url) && isset($back_text)): ?>
        <a href="<?=$back_url?>" class="btn btn-default"><?=$back_text?></a>
    <?php endif ?>
</div></div>