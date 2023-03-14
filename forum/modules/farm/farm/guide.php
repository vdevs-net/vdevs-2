<?php
defined('_MRKEN_CMS') or die('Error: restricted access');

$page_title = 'Hướng dẫn nông trại';
require(ROOTPATH . 'system/header.php');

$breadcrumb = new breadcrumb();
$breadcrumb->add('/farm/', 'Nông trại');
$breadcrumb->add('Hướng dẫn');
$_breadcrumb = $breadcrumb->out();

?>
<div class="farm_wrapper">
<div class="menu">
    <h3>Cửa hàng</h3>
    <ul>
        <li>Là nơi bạn có thể mua cái giống cây trồng, các loại vật nuôi và các vật phẩm hỗ trợ</li>
    </ul>
</div>
<div class="menu">
    <h3>Nhà kho</h3>
    <ul>
        <li>Là nơi chứa các vật phẩm bạn đã mua và các sản phẩm thu hoạch từ nông trại</li>
        <li>Tại nhà kho bạn có thể bán các sản phẩm của mình bằng cách chọn các sản phẩm muốn bán và chọn <b>Bán</b></li>
    </ul>
</div>
<div class="menu">
    <h3>Cây khế</h3>
    <ul>
        <li>Cây khế là một nguồn thu nhập miễn phí có giá trị lớn trong nông trại</li>
        <li>Cây khế ban đầu chưa có cấp độ, bạn có thể nâng cấp lên tối đa là cấp 12</li>
        <li>Cây khế cấp càng cao thì sản lượng càng lớn và thời gian thu hoạch càng ngắn</li>
    </ul>
</div>
<div class="menu">
    <h3>Đơn vị nông trại</h3>
    <ul>
        <li>Trong nông trại, có 3 loại đơn vị: cây trồng, vật nuôi và cá</li>
        <li>Mỗi người chơi khởi đầu với 6 đơn vị cây trồng, 10 đơn vị vật nuôi và 3 đơn vị ao cá</li>
        <li>Người chơi có thể mua thêm lên đến tối đa 48 đơn vị cây trồng, 20 đơn vị vật nuôi và 10 đơn vị ao cá</li>
    </ul>
</div>
<div class="menu">
    <h3>Hành động</h3>
    <ul>
        <li>Để thực hiện hành động lên một đơn vị, bạn cần chọn đơn bị bằng cách sử dụng bộ chọn trong mục hành động hoặc chọn từng đơn vị bằng cách nhấp vào đơn vị đó. Riêng ô đất thì có thể chọn nhiều ô cùng lúc bằng cách chọn vào ô dưới mỗi ô đất</li>
        <li>Hành động "Trồng" chỉ sử dụng khi bạn chọn mục tiêu là ô đất</li>
        <li>Chọn hành động "<b>Tưới nước</b>/<b>Cho ăn</b>" sẽ thực hiện <b>tưới nưới</b> nếu mục tiêu là <b>cây trồng</b> và <b>cho ăn</b> nếu mục tiêu là <b>vật nuôi</b> hoặc <b>cá</b>. <b>Tưới nước</b> sẽ không mất phí, còn <b>cho ăn</b> sẽ tốn 1 xu/đơn vị/lần.</li>
        <li>Chọn hành động "<b>Thu hoạch</b>/<b>Bán</b>" sẽ thực hiện <b>thu hoạch</b> nếu mục tiêu là <b>cây trồng</b> và <b>bán</b> nếu mục tiêu là <b>vật nuôi</b> hoặc <b>cá</b>. Bạn chỉ có thể thu hoạch cây trồng khi cây đã trưởng thành. Sản phẩm thu hoạch từ <b>cây trồng</b> sẽ được thêm vào kho. Bạn có thể bán vật nuôi khi chưa trưởng thành, tuy nhiên số xu nhận được sẽ ít hơn so với vật nuôi trưởng thành</li>
        <li>Sản phẩm từ vật nuôi sẽ tỉ lệ với sức khỏe của vật nuôi. Do đó trước khi thu sản phẩm từ vật nuôi, hãy thu hoạch lúc sức khỏe của vật nuôi là 100%. Sản phẩm từ vật nuôi sẽ được thêm vào kho.</li>
        <li>Lưu ý: trước khi bán vật nuôi, nếu vật nuôi có sản phẩm thì bạn cần thu hoạch sản phẩm trước, nếu không sản phẩm sẽ bị mất.</li>
    </ul>
</div>
</div>

<?php

$tpl_data['rendered_content'] = ob_get_clean();
