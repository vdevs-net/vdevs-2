<?php
if (!$is_ajax) {
    $this->layout('layout');
}
?>
<form action="<?=$form_action?>" method="post">
<div class="menu">
     <p>
          <h3><?=$lang['link']?></h3>
          <input type="text" name="link" value="<?=$data['link']?>"/><br />
          <input type="checkbox" name="show"<?php if ($data['show']): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['link_direct']?><br />
          <small><?=$lang['link_direct_help']?></small>
     </p>
     <p>
          <h3><?=$lang['title']?></h3>
          <input type="text" name="name" value="<?=$data['name']?>"/><br />
          <small><?=$lang['link_add_name_help']?></small>
     </p>
     <p>
          <h3><?=$lang['color']?></h3>
          <input type="text" name="color" size="6" value="<?=$data['color']?>"/><br />
          <small><?=$lang['link_add_color_help']?></small>
     </p>
     <p>
          <h3><?=$lang['transitions']?></h3>
          <input type="text" name="count" size="6" value="<?=$data['count_link']?>"/><br />
          <small><?=$lang['link_add_trans_help']?></small>
     </p>
     <p>
          <h3><?=$lang['days']?></h3>
          <input type="text" name="day" size="6" value="<?=$data['day']?>"/><br />
          <small><?=$lang['link_add_days_help']?></small>
     </p>
</div>
<div class="menu">
     <p>
          <h3><?=$lang['to_show']?></h3>
          <input type="radio" name="view" value="0"<?php if (!$data['view']): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['to_all']?><br />
          <input type="radio" name="view" value="1"<?php if ($data['view'] == 1): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['to_guest']?><br />
          <input type="radio" name="view" value="2"<?php if ($data['view'] == 2): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['to_users']?>
     </p>
     <p>
          <h3><?=$lang['arrangement']?></h3>
          <input type="radio" name="type" value="0"<?php if (!$data['type']): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['links_armt_over_logo']?><br />
          <input type="radio" name="type" value="1"<?php if ($data['type'] == 1): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['links_armt_under_usermenu']?><br />
          <input type="radio" name="type" value="2"<?php if ($data['type'] == 2): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['links_armt_over_counters']?><br />
          <input type="radio" name="type" value="3"<?php if ($data['type'] == 3): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['links_armt_under_counters']?>
     </p>
     <p>
          <h3><?=$lang['placing']?></h3>
          <input type="radio" name="layout" value="0"<?php if (!$data['layout']): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['link_add_placing_all']?><br />
          <input type="radio" name="layout" value="1"<?php if ($data['layout'] == 1): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['link_add_placing_front']?><br />
          <input type="radio" name="layout" value="2"<?php if ($data['layout'] == 2): ?> checked="checked"<?php endif ?> />&nbsp;<?=$lang['link_add_placing_child']?>
     </p>
</div>
<div class="menu"><input type="submit" name="submit" value="<?=$submit_text?>" /></div></form>