<?php
    $this->layout('layout');
?>
<div class="gmenu"><img src="<?=$logo_src?>" alt="logo" /></div>
<div class="topmenu center"><a href="<?=$logo_src?>">Tải về</a></div>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <h3>Văn bản:</h3>
        <input type="text" name="text" value="<?=$input_text?>" autocomplete="off" />
    </div>
    <div class="menu">
        <h3>Tùy chọn</h3>
        <label class="radio"><input type="radio" name="style" value="1"<?php if ($input_style == 1): ?> checked="checked"<?php endif ?> /> Chữ trắng không nền</label>
        <label class="radio"><input type="radio" name="style" value="2"<?php if ($input_style == 2): ?> checked="checked"<?php endif ?> /> Chữ xanh dương không nền</label>
    </div>
    <div class="list1"><input type="submit" value="Tạo Ngay"/></div>
</form>