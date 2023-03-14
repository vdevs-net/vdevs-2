<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<div class="menu">
    <form action="usr-clean" method="post">
    <p><h3><?=$lang['dead_profiles']?></h3><?=$lang['dead_profiles_desc']?></p>
    <p><?=$lang['total']?>: <b><?=$total?></b></p>
    <p><input type="submit" name="submit" value="<?=$lang['delete']?>"/></p>
    </form>
</div>