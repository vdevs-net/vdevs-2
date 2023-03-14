<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>

<?= $rendered_content ?>