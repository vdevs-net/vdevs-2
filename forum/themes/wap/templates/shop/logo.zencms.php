<?php
    $this->layout('layout');
?>
<div class="rmenu"><?=$description?></div>
<div class="gmenu"><img src="<?=$logo_src?>" alt="logo" /></div>
<div class="topmenu center"><a href="<?=$logo_src?>">Tải về</a></div>
<form action="<?=$form_action?>" method="post">
    <div class="menu">
        <h3>Tên miền</h3>
        <input type="text" name="text1" value="<?=$input_text1?>" autocomplete="off" />
    </div>
    <div class="menu">
        <h3>Đuôi tên miền</h3>
        <input type="text" name="text2" value="<?=$input_text2?>" autocomplete="off" />
    </div>
    <div class="menu">
        <h3>Văn bản</h3>
        <input type="text" name="text3" value="<?=$input_text3?>" autocomplete="off" />
    </div>
    <div class="menu">
        <h3>Vị trí văn bản theo chiều ngang</h3>
        <input type="text" name="position" value="<?=$input_position?>" autocomplete="off" />
    </div>
    <div class="list1"><input type="submit" value="Tạo Ngay" /></div>
</form>