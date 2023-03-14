<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<?php if ($refresh): ?>
    <div class="gmenu"><?=$lang['refresh_descriptions_ok']?></div>
<?php endif ?>
<div class="menu"><form action="languages" method="post"><p>
    <table>
        <tr><td>&nbsp;</td><td style="padding-bottom:4px"><h3><?=$lang['language_system']?></h3></td></tr>
        <?php foreach ($languages as $key => $val): ?>
            <tr>
            <td valign="top"><input type="radio" value="<?=$key?>" name="iso"<?php if ($key == $set_lng): ?> checked="checked"<?php endif ?> /></td>
                <td style="padding-bottom:6px"><img src="<?=SITE_URL?>/assets/images/flags/<?=$key?>.gif" alt=""/>&#160;<b><?=$val['name']?></b> <span class="green">[<?=$key?>]</span></td>
            </tr>
        <?php endforeach ?>
        <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="<?=$lang['save']?>" /></td></tr>
    </table></p>
</form></div>
<div class="phdr"><?=$lang['total']?>: <b><?=count($languages)?></b></div>
<div class="menu"><a href="languages?refresh"><?=$lang['refresh_descriptions']?></a></div>