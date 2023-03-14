<?php
    $this->layout('layout');
?>
<div class="gmenu">
        <form action="<?=$form_action?>" method="get">
        <p>
            <input type="text" value="<?=$input_search?>" name="search" /><input type="submit" value="<?=$lang['search']?>" /><br />
            <input name="t" type="checkbox" value="1"<?=($input_search_t ? ' checked="checked"' : '')?> />&nbsp;<?=$lang['search_topic_name']?>
        </p>
        </form>
</div>
<?php if ($show_result): ?>
    <div class="phdr"><?=$lang['search_results']?></div>
    <?php if ($total): ?>
        <?php if ($pagination): ?><div class="topmenu"><?=$pagination?></div><?php endif ?>
        <?php foreach ($results as $item): ?>
            <div class="menu">
                <h4><a href="<?=$item['thread_url']?>"><b><?=$item['thread_name']?></b></a></h4>
                <?php if ($item['tags']): ?>
                    <div><b>Tags</b>: <i><?=$item['tags']?></i></div>
                <?php endif ?>
                <div><b><?=$item['author']?></b> <span class="gray">(<?=$item['time']?>)</span></div>
                <div><?=$item['description']?></div>
            </div>
        <?php endforeach ?>
        <?php if ($pagination): ?>
            <div class="phdr"><?=$lang['total']?>: <?=$total?></div>
            <div class="topmenu"><?=$pagination?></div>
        <?php endif ?>
    <?php else: ?>
        <div class="rmenu"><p><?=$lang['search_results_empty']?></p></div>
    <?php endif ?>
<?php else: ?>
    <?php if ($error): ?>
        <div class="rmenu"><?=$error?></div>
    <?php endif ?>
    <div class="notif"><small><?=$lang['search_help']?></small></div>
<?php endif ?>