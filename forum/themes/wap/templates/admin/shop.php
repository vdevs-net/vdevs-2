<?php
    $this->layout('layout');
?>
<?php if ($settings_saved): ?>
    <div class="rmenu"><?=$lang['settings_saved']?></div>
<?php endif ?>
<form action="shop" method="post">
    <div class="menu">
        <div><h3>Khuyến mãi thẻ nạp</h3><input type="text" name="offer" value="<?=$set_offer?>" style="width:50px"/> %</div>
        <p><input type="submit" name="submit" value="<?=$lang['save']?>"/></p>
    </div>
</form>