<?php
    $this->layout('layout');
?>
<?php foreach ($items as $item): ?>
    <div class="menu"><a href="<?=$item['url']?>"><?=$item['name']?></a></div>
<?php endforeach ?>